cd ~/maxcomStore/back
git fetch
git rebase
sudo docker exec -it healtrition-backend-1 php artisan optimize
sudo docker exec -it healtrition-backend-1 supervisorctl reread
sudo docker exec -it healtrition-backend-1 supervisorctl update
sudo docker exec -it healtrition-backend-1 supervisorctl restart healtrition_api:*
