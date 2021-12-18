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
        sh '''docker build . \\
-f backend.dockerfile \\
-t tumbler-backend-api;'''
      }
    }

    stage('Run The Docker Container') {
      steps {
        sh '''docker run \\
--name backend-api \\
--entrypoint /bin/bash \\
-dt --rm tumbler-backend-api;'''
      }
    }

    stage('Lint') {
      parallel {
        stage('Lint') {
          steps {
            sh 'docker exec backend-api bash -c ./lint.sh;'
          }
        }

        stage('Test') {
          steps {
            sh 'docker exec backend-api bash -c ./test.sh;'
          }
        }

      }
    }

    stage('Stop The Docker Container') {
      steps {
        sh 'docker container stop tumbler-backend-api'
      }
    }

    stage('List Docker Images & Containers') {
      steps {
        sh 'docker image ls;'
        sh 'docker image ls -a;'
        sh 'docker container ls;'
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
      }
    }

  }
}