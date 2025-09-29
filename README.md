## Start application in localhost

```bash
docker-compose -f docker/local/docker-compose.yml --env-file .env up -d --build
```

## Start application in production

Run jenkins container and create a pipeline to build from Jenkins file in the project root location.

```bash
git clone https://github.com/HouiderWalid/jenkins-docker-k8s-aws
```

These environment variables are required in jenkins credentials however:

```bash
ENV_FILE= # .env file to upload as secret file credential
AWS_ACCESS_DATA= # here to put the username (access key) and password (secret access key)
AWS_REGION= # aws region as secret text credential
ECR_REPOSITORY= # aws ecr repository name as secret text credential
AWS_ACCOUNT_ID= # aws account id as secret text credential
EKS_CLUSTER_NAME= # aws eks cluster name as secret text credential
```