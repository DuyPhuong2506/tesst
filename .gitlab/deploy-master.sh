aws ec2 describe-instances  --filters "Name=tag:aws:autoscaling:groupName,Values=WeddingOnlineApi" --region ap-northeast-1 | grep PrivateIpAddress | awk '{print $2}' | uniq -u |  tr -d '"' | tr -d ',' | tr -d '[' | grep -v '^$' > ~/list-api-ip.txt
echo "List API instance:" \n
cat ~/list-api-ip.txt

USER=centos
SOURCE="/srv/WeddingOnline-api/"

rsync -hrzog --chown centos:centos --delete --bwlimit=8192 --timeout=600 --rsync-path="sudo rsync" -e "ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null" ./ $USER@10.1.3.48:$SOURCE
ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null $USER@10.1.3.48 " cd /srv/WeddingOnline-api/ && sudo php artisan cache:clear" &
ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null $USER@10.1.3.48 " cd /srv/WeddingOnline-api/ && sudo php artisan config:clear" &

while read IP; do
    echo "Deploy code to instance: " $IP
    rsync -hrzog --chown centos:centos --delete --bwlimit=8192 --timeout=600 --rsync-path="sudo rsync" -e "ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null" ./ $USER@$IP:$SOURCE
done < ~/list-api-ip.txt

while read IP; do
    echo "Clear cache... "
    ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null $USER@$IP " cd /srv/WeddingOnline-api/ && sudo php artisan cache:clear" &
    ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null $USER@$IP " cd /srv/WeddingOnline-api/ && sudo php artisan config:clear" &
done < ~/list-api-ip.txt
