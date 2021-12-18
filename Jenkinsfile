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

    stage('Build The Docker Image') {
      steps {
        sh '''cd Backend;
docker build . \\
-f backend.dockerfile \\
-t tumbler-backend-api;'''
      }
    }

    stage('Run The Docker Container') {
      steps {
        sh '''docker run \\
--name tumbler-backend-api \\
--entrypoint /bin/bash \\
-dt --rm tumbler-backend-api;'''
      }
    }

    stage('Lint & Test') {
      parallel {
        stage('Lint') {
          steps {
            sh 'docker exec tumbler-backend-api bash -c \' bash lint.sh\';'
          }
        }

        stage('Test') {
          steps {
            sh 'docker exec tumbler-backend-api bash -c \' bash test.sh\';'
          }
        }

      }
    }

    stage('List Docker Images & Containers') {
      steps {
        sh 'docker image ls -a;'
        sh 'docker container ls -a;'
      }
    }

    stage('Deploy To dev-server') {
      agent {
        node {
          label 'dev-server'
        }

      }
      steps {
        sh '''hostname;
whoami;
uptime;'''
        sh '''cd Backend;
#docker-compose up;'''
      }
    }

  }

  post {
    always {
      sh 'docker container stop tumbler-backend-api;'
      sh 'docker image remove tumbler-backend-api;'
      deleteDir()
    }
  }
}