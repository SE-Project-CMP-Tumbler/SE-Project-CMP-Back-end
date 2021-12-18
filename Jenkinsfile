pipeline {
  agent any
  stages {
    stage('Debug Info') {
      steps {
        sh 'whoami;hostname;uptime'
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
        sh 'whoami;hostname;uptime'
        sh '''cd Backend;
#docker-compose down;
#az storage file download --account-name tumblerstorageaccount -s tumbler-secrets -p backend.env --dest .env;
#docker system prune -af;
#docker-compose up -d;'''
      }
      post {
        always {
          discordSend(
            title: JOB_NAME,
            link: env.BUILD_URL,
            result: currentBuild.currentResult,
            webhookURL: 'https://discord.com/api/webhooks/921772869782994994/mi4skhArIoT6heXWebPiWLn6Xc95rZgUqtW7qriBOYvnl0sTdfn16we7yPY-n-DJYRmH'
          )
        }

        unsuccessful {
          sh '''cd Backend;
#some mechanism to roll back to the latest working images, we shall tag them and not to prune them if they were unused'''
        }
      }
    }
  }

  post {
    unsuccessful {
      sh 'docker container stop tumbler-backend-api && true'
      sh 'docker image remove tumbler-backend-api && true'
    }

    cleanup {
      cleanWs()
    }
  }
}