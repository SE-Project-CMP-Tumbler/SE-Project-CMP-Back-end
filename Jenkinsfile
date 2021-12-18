pipeline {
  agent any
  stages {
    stage('Test') {
      steps {
        echo 'Hi from Jenkins agent on aws.'
        sh 'whoami'
        sh 'host'
      }
    }
  }
}
