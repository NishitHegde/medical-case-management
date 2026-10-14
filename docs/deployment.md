# AWS Production Deployment Architecture & Guide

This document outlines a secure, scalable production deployment strategy for the **Medical Case Management System (Jajal Medical)** on Amazon Web Services (AWS), following HIPAA security principles and AWS Well-Architected Framework best practices.

---

## 1. High-Level Architecture Diagram

```text
[ Internet / Clinical Users ]
             │
             ▼ HTTPS (443)
  [ AWS Route 53 (DNS) ]
             │
             ▼
  [ AWS CloudFront (CDN) / AWS WAF (Web Application Firewall) ]
             │
             ▼
[ Application Load Balancer (ALB) ] (SSL Termination via AWS Certificate Manager)
             │
   ┌─────────┴─────────┐ (Private Subnets - Multi-AZ)
   ▼                   ▼
[ EC2 Instance 1 ]  [ EC2 Instance 2 ] (Auto Scaling Group, Nginx + PHP 8.2-FPM)
   │                   │
   ├───────────────────┴───────────────────┐
   ▼                                       ▼
[ Amazon RDS for MySQL (Multi-AZ) ]     [ Amazon S3 (Encrypted at Rest) ]
(Primary + Standby Replica)             (Clinical CAD STL/DICOM Models)
```

---

## 2. Infrastructure Components

### A. Compute: Amazon EC2 & Auto Scaling
- **Instances**: `t3.medium` or `c6i.large` running Ubuntu LTS 22.04.
- **Web Stack**: Nginx reverse proxy passing requests to `php-fpm` (PHP 8.2).
- **Auto Scaling Group**: Configured across multiple Availability Zones (AZs) with CPU and request-based scaling policies.
- **Placement**: EC2 instances reside exclusively within **Private Subnets** without public IP addresses. Outbound traffic (e.g. Composer packages, OS patches, external Public Holiday API) flows through an **AWS NAT Gateway**.

### B. Database: Amazon RDS for MySQL
- **Engine**: MySQL 8.0 with InnoDB engine.
- **Deployment**: Multi-AZ standby replica for zero-data-loss failover.
- **Storage**: General Purpose SSD (gp3) or Provisioned IOPS (io2) with automated daily snapshots and 30-day point-in-time recovery.
- **Encryption**: Storage encryption enabled using AWS KMS Customer Managed Keys (CMK).
- **Access**: Strictly restricted to the EC2 security group on port 3306; not publicly accessible.

### C. Object Storage: Amazon S3
- **Usage**: Archiving 3D scan files (CT/DICOM), CAD designs, and patient-specific implant specifications.
- **Security**:
  - SSE-KMS (Server-Side Encryption with AWS Key Management Service).
  - S3 Block Public Access enabled at account and bucket levels.
  - Bucket Policies permitting access only via IAM roles attached to the EC2 instances.

---

## 3. Security Groups Configuration

| Security Group | Inbound Rules | Outbound Rules | Purpose |
|---|---|---|---|
| **ALB-SG** | Port 80 (HTTP) from `0.0.0.0/0` (Redirects to 443)<br>Port 443 (HTTPS) from `0.0.0.0/0` | Port 80/8080 to `EC2-SG` | Internet-facing load balancer |
| **EC2-SG** | Port 80/8080 from `ALB-SG`<br>Port 22 (SSH) via AWS Systems Manager (SSM) | Port 3306 to `RDS-SG`<br>Port 443 to NAT Gateway | Application runtime instances |
| **RDS-SG** | Port 3306 from `EC2-SG` | None | Dedicated database isolation |

---

## 4. Secrets Management & Environment Isolation

1. **AWS Secrets Manager**:
   - Environment variables (`APP_KEY`, `DB_PASSWORD`, `DB_USERNAME`) are stored in AWS Secrets Manager, not on the disk in cleartext `.env`.
   - On deployment or container boot, a bootstrap script fetches secrets and injects them as process environment variables.
2. **IAM Roles**:
   - EC2 instances assume an IAM instance profile with least-privilege permissions:
     - `secretsmanager:GetSecretValue` for application credentials.
     - `s3:GetObject`, `s3:PutObject` for clinical models.

---

## 5. SSL/TLS & HTTPS Configuration

1. **Certificate Management**:
   - Managed SSL/TLS certificate issued via **AWS Certificate Manager (ACM)** for `cases.jajalmedical.com`.
   - Certificates auto-renew without manual intervention.
2. **ALB Listener**:
   - Listener 80: HTTP redirect rule forwarding to HTTPS 443 (`HTTP_301`).
   - Listener 443: Modern TLS 1.3 security policy (`ELBSecurityPolicy-TLS13-1-2-2021-06`).
3. **Security Headers**:
   - Nginx injects HSTS (`Strict-Transport-Security`), `X-Frame-Options: SAMEORIGIN`, and `X-Content-Type-Options: nosniff`.

---

## 6. Step-by-Step Production Release Workflow

```bash
# 1. Update codebase on server
git pull origin main

# 2. Install production dependencies (exclude dev tools)
composer install --no-dev --optimize-autoloader --no-interaction

# 3. Cache Laravel configurations and routes
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Run database migrations safely
php artisan migrate --force

# 5. Restart PHP-FPM to load new bytecode
sudo systemctl restart php8.2-fpm
sudo systemctl reload nginx
```
