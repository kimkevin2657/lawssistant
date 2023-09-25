exit
mariadb --version
sudo vi /etc/yum.repos.d/MariaDB.repo
su - centos
ls
l
ll
cd ..
l
history
exit
sudo vi /etc/yum.repos.d/MariaDB.repo
sudo yum update -y
sudo yum install MariaDB-server MariaDB-client -y
sudo systemctl start mariadb
sudo systemctl enable mariadb
sudo mysql_secure_installation
sudo mysql -u root -p
sudo vi /etc/my.cnf.d/server.cnf
sudo systemctl restart mariadb
sudo firewall-cmd --zone=public --add-port=3306/tcp --permanent
sudo firewall-cmd --reload
sudo firewall-cmd --add-port=22/tcp --permanent
sudo firewall-cmd --add-port=80/tcp --permanent
sudo firewall-cmd --reload
sudo firewall-cmd --permanent --zone=public --add-rich-rule='rule family="ipv4" port port=3306 protocol=tcp drop'
sudo firewall-cmd --permanent --zone=public --add-rich-rule='rule family="ipv4" source address="110.8.162.104" port port=3306 protocol=tcp accept'
sudo firewall-cmd --permanent --zone=public --add-rich-rule='rule family="ipv4" source address="220.88.200.247" port port=3306 protocol=tcp accept'
sudo firewall-cmd --permanent --zone=public --add-rich-rule='rule family="ipv4" source address="112.151.181.136" port port=3306 protocol=tcp accept'
sudo firewall-cmd --reload
sudo vi /etc/my.cnf
sudo systemctl restart mariadb
sudo firewall-cmd --permanent --zone=public --add-rich-rule='rule family="ipv4" source address="1.252.60.66" port port=3306 protocol=tcp accept'
sudo firewall-cmd --reload
sudo firewall-cmd --permanent --zone=public --remove-rich-rule='rule family="ipv4" port port=3306 protocol=tcp drop'
sudo firewall-cmd --permanent --zone=public --remove-rich-rule='rule family="ipv4" source address="110.8.162.104" port port=3306 protocol=tcp accept'
sudo firewall-cmd --permanent --zone=public --remove-rich-rule='rule family="ipv4" source address="220.88.200.247" port port=3306 protocol=tcp accept'
sudo firewall-cmd --permanent --zone=public --remove-rich-rule='rule family="ipv4" source address="112.151.181.136" port port=3306 protocol=tcp accept'
sudo firewall-cmd --permanent --zone=public --remove-rich-rule='rule family="ipv4" source address="1.252.60.66" port port=3306 protocol=tcp accept'
sudo firewall-cmd --reload
sudo firewall-cmd --permanent --zone=public --add-port=3306/tcp
sudo firewall-cmd --permanent --zone=public --add-port=21/tcp
sudo firewall-cmd --permanent --zone=public --add-port=22/tcp
sudo firewall-cmd --permanent --zone=public --add-port=21/tcp
sudo systemctl status vsftpd
sudo vi /etc/vsftpd/vsftpd.conf
sudo setenforce 0
sudo firewall-cmd --list-all
sudo vi /etc/vsftpd/vsftpd.conf
sudo systemctl restart vsftpd
sudo vi /etc/vsftpd/vsftpd.conf
sudo systemctl restart vsftpd
sudo setsebool -P ftpd_full_access 1
sudo vi /etc/vsftpd/vsftpd.conf
sudo firewall-cmd --permanent --zone=public --add-port=2200/tcp
sudo semanage port -a -t ftp_port_t -p tcp 2200
sudo vi /etc/vsftpd/vsftpd.conf
sudo firewall-cmd --permanent --zone=public --add-port=40000-40100/tcp
sudo firewall-cmd --reload
sudo setsebool -P ftpd_full_access 1
sudo systemctl restart vsftpd
sudo cp /etc/vsftpd/vsftpd.conf /etc/vsftpd/vsftpd.conf.bak
sudo ls /etc/vsftpd
sudo vi /etc/vsftpd/vsftpd.conf
sudo systemctl restart vsftpd
sudo firewall-cmd --add-port=2200/tcp --permanent
sudo firewall-cmd --reload
sudo netstat -tuln | grep 2200
sudo vi /etc/vsftpd/vsftpd.conf
sudo firewall-cmd --add-port=2200/tcp --permanent
녀애
sudo
sudo su
ls
cd /var/www/html
ls
cd www
ls
