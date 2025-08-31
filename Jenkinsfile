pipeline {
  agent any

  environment {
    // Docker Hub credentials configured in Jenkins (ID: dockerhub-creds)
    DOCKERHUB = credentials('dockerhub-creds')
    DOCKERHUB_USER = "${DOCKERHUB_USR}"
    DOCKERHUB_PASS = "${DOCKERHUB_PSW}"
    IMAGE_NAME = "applebite"
  }

  options {
    timestamps()
    disableConcurrentBuilds()
  }

  stages {
    stage('Checkout') {
      steps {
        checkout scm
        script {
          env.SHORT_SHA = sh(script: "git rev-parse --short HEAD", returnStdout: true).trim()
          echo "Commit: ${env.SHORT_SHA}"
        }
      }
    }

    stage('Build Docker Image') {
      steps {
        sh '''
          echo "$DOCKERHUB_PASS" | docker login -u "$DOCKERHUB_USER" --password-stdin
          docker build -t "$DOCKERHUB_USER/$IMAGE_NAME:$SHORT_SHA" .
        '''
      }
    }

    stage('Push Image') {
      steps {
        sh '''
          docker push "$DOCKERHUB_USER/$IMAGE_NAME:$SHORT_SHA"
          docker logout
        '''
      }
    }

    stage('Provision Test & Prod (first run safe)') {
      steps {
        sh '''
          ansible-galaxy collection install community.docker -q
          # Provision both environments; safe to re-run (idempotent)
          ansible-playbook -i ansible/inventories/test/hosts ansible/provision.yml
          ansible-playbook -i ansible/inventories/prod/hosts ansible/provision.yml
        '''
      }
    }

    stage('Deploy to Test') {
      steps {
        sh '''
          ansible-playbook -i ansible/inventories/test/hosts ansible/deploy.yml \
            -e target_env=test -e image_tag="$SHORT_SHA" \
            -e dockerhub_namespace="$DOCKERHUB_USER"
        '''
      }
    }

    stage('Smoke Test (HTTP 200)') {
      steps {
        sh '''
          TEST_IP=$(awk '/test1/ {print $2}' ansible/inventories/test/hosts | cut -d= -f2)
          echo "Testing http://$TEST_IP/ ..."
          for i in $(seq 1 10); do
             code=$(curl -s -o /dev/null -w "%{http_code}" "http://$TEST_IP/")
             if [ "$code" = "200" ]; then
               echo "Smoke test passed with HTTP 200"
               exit 0
             fi
             echo "Attempt $i: got $code, retrying in 3s..."
             sleep 3
          done
          echo "Smoke test failed"
          exit 1
        '''
      }
    }

    stage('Approve Prod') {
      steps {
        input message: 'Promote to Production?', ok: 'Deploy'
      }
    }

    stage('Deploy to Prod') {
      steps {
        sh '''
          ansible-playbook -i ansible/inventories/prod/hosts ansible/deploy.yml \
            -e target_env=prod -e image_tag="$SHORT_SHA" \
            -e dockerhub_namespace="$DOCKERHUB_USER"
        '''
      }
    }
  }

  post {
    always {
      archiveArtifacts artifacts: 'ansible/**', onlyIfSuccessful: false
    }
  }
}
