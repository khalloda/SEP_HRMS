# Architecture Constraints

## TL;DR

This document outlines the technical, organizational, and political constraints that influence the system architecture.

## Technical Constraints

### Hardware Constraints

- Deployment on GoDaddy cPanel hosting
- No SSH access to production servers
- Limited server resources (shared hosting)
- ZKTeco biometric devices for attendance

### Software Constraints

- PHP 8.4 / Laravel 10 framework
- MySQL 8.0 database
- Bootstrap 5 frontend
- UTF8MB4 charset for Arabic support

### System Constraints

- Browser compatibility (modern browsers)
- Mobile responsiveness required
- Maximum file upload size: 10MB
- Session timeout requirements

## Organizational Constraints

### Budget Constraints

- Commercial hosting only
- Limited third-party service usage
- Open-source preference for libraries
- In-house development team

### Time Constraints

- Phased deployment approach
- Critical features prioritized
- Regular security updates required
- Monthly release cycle

### Resource Constraints

- Small development team
- No dedicated DevOps
- Limited testing resources
- Part-time security review

## Political/Legal Constraints

### Data Protection

- Sensitive data encryption required
- National ID number protection
- Salary information confidentiality
- Document access controls

### Compliance Requirements

- Bar Association regulations
- Labor law compliance
- Data retention policies
- Audit trail requirements

### Localization Requirements

- Bilingual support (English/Arabic)
- RTL layout support
- Date/time format localization
- Currency format localization

## Conventions

### Documentation

- Markdown format
- Code comments in English
- API documentation required
- Architecture decisions recorded

### Development

- PSR coding standards
- Git workflow
- Code review required
- Unit test coverage goals
