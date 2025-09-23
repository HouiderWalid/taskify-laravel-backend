pipeline{
    agent any
    environment {
        ENV_FILE = credentials('taskify-laravel-backend-env-file')
        AWS_ACCESS_DATA = credentials('taskify-aws-secret')
        AWS_REGION = credentials('taskify-aws-region')
        ECR_REPOSITORY = credentials('taskify-aws-ecr-repository-name')
        AWS_ACCOUNT_ID = credentials('taskify-aws-account-id')
    }
    stages {
        stage('Test') {
            steps {
                sh 'docker build -t taskify-laravel-backend-test -f docker/prod/php/Dockerfile .'
                sh 'docker run -d --name taskify-laravel-backend-test-1 taskify-laravel-backend-test:latest'
                sh 'docker cp "$ENV_FILE" taskify-laravel-backend-test-1:/var/www/html/.env'
                sh 'docker exec -i -e COMPOSER_ALLOW_SUPERUSER=1 taskify-laravel-backend-test-1 composer install --no-interaction'
                sh 'docker exec -i taskify-laravel-backend-test-1 chown -R www-data:www-data /var/www/html'
                sh 'docker exec -i taskify-laravel-backend-test-1 php artisan test --testsuite=Feature'
            }
            post {
                always {
                    sh 'docker stop taskify-laravel-backend-test-1'
                    sh 'docker rm taskify-laravel-backend-test-1'
                    sh 'docker rmi taskify-laravel-backend-test:latest'
                }
            }
        }
        stage('Deploy') {
            steps {
                sh 'aws configure set aws_access_key_id "$AWS_ACCESS_DATA_USR"'
                sh 'aws configure set aws_secret_access_key "$AWS_ACCESS_DATA_PSW"'
                sh 'docker build -t taskify-laravel-backend -f docker/prod/php/Dockerfile .'
                sh 'aws ecr get-login-password --region "$AWS_REGION" | docker login --username AWS --password-stdin "$AWS_ACCOUNT_ID".dkr.ecr."$AWS_REGION".amazonaws.com'
                sh 'docker tag taskify-laravel-backend:latest "$AWS_ACCOUNT_ID".dkr.ecr."$AWS_REGION".amazonaws.com/"$ECR_REPOSITORY":latest'
                sh 'docker push "$AWS_ACCOUNT_ID".dkr.ecr."$AWS_REGION".amazonaws.com/"$ECR_REPOSITORY":latest'
            }
        }
    }
}