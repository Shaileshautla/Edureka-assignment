# Edureka DevOps Project – AWS CI/CD (Jenkins + Ansible + Docker)

This repo is a complete, minimal end‑to‑end implementation scaffold for the Edureka certification project.
It deploys a PHP app to Test and Prod EC2 servers via Jenkins pipelines, Docker images, and Ansible.

## Architecture
- Jenkins Controller (EC2, Ubuntu 22.04) runs the pipeline and Ansible.
- Test Server (EC2) and Prod Server (EC2) run Docker containers of the app.
- Docker images are pushed to Docker Hub (simplest for demo).
  > You can switch to AWS ECR later if desired.

## Quick Start (high level)
1) Fork the sample app or use this scaffold's `/app` as a starter.
2) Launch 3 EC2 instances (Jenkins, Test, Prod) and allow:
   - Jenkins: 22 (your IP), 8080 (your IP or public if using webhook)
   - Test/Prod: 22 (Jenkins IP), 80 (0.0.0.0/0)
3) On Jenkins EC2: install Java, Jenkins, Ansible, Docker (see guide in your chat).
4) Put your EC2 private key at `/var/lib/jenkins/.ssh/aws_key.pem` and `chmod 600` it.
5) Edit `ansible/inventories/*/hosts` to set public IPs, usernames, and key path if needed.
6) In Jenkins, add Docker Hub creds:
   - ID: `dockerhub-creds` (username/password)
7) Create a Multibranch or Pipeline job using this repo and set **GitHub hook trigger**.
8) Run the pipeline; it will:
   - Build and push a Docker image to Docker Hub (tag = commit SHA)
   - Provision Docker on Test & Prod (first run)
   - Deploy container to Test
   - Await manual Approval
   - Deploy container to Prod

## Evidence to capture
- Jenkins job config, build history, console logs
- Docker Hub repository with tags
- Ansible run output (provisioning & deploy)
- Browser screenshots of Test and Prod URLs
- Git commit history and GitHub webhook settings
# Edureka-assignment
