pipeline {
  agent any
  stages {
    stage('Debug Info') {
      steps {
        sh 'whoami'
        sh 'hostname'
        sh 'uptime'
      }
    }

    stage('Build Docker Image') {
      steps {
        sh '''cd Backend;
docker build . \\
-f backend.dockerfile \\
-t tumbler-backend-api'''
      }
    }

    stage('Run Container') {
      steps {
        sh '''docker run \\
--name tumbler-backend-api \\
--entrypoint /bin/bash \\
-dt --rm tumbler-backend-api'''
      }
    }

    stage('Lint') {
      steps {
        sh 'docker exec tumbler-backend-api bash -c \'bash lint.sh\''
      }
    }

    stage('Test') {
      steps {
        sh 'docker exec tumbler-backend-api bash -c \'bash test.sh\''
      }
    }

    stage('Stop Container & Remove Image') {
      steps {
        sh 'docker container stop tumbler-backend-api'
        sh 'docker image remove tumbler-backend-api'
      }
    }

    stage('List Docker Images & Containers') {
      steps {
        sh 'docker image ls -a'
        sh 'docker container ls -a'
      }
    }

    stage('Deploy To dev-server') {
      agent {
        node {
          label 'dev-server'
        }
      }
      when {
        branch 'devops'
      }
      steps {
        sh '''hostname;
whoami;
uptime;'''
        sh '''cd Backend;
#docker-compose down;
#az storage file download --account-name tumblerstorageaccount -s tumbler-secrets -p backend.env --dest .env;
#docker system prune -f;
#docker-compose up -d;
'''
      }
    }
  }

  post {
    failure {
      sh 'docker container stop tumbler-backend-api && true'
      sh 'docker image remove tumbler-backend-api && true'
    }

    always {
      cleanWs()
    }
  }
}