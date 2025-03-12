Prvotní instalace aplikace do produkce
======================================

    # file: config/deploy.yml
    production:
      server: "alpha.example.com"
      user: "deploy"
      directory: "/home/deploy/webapps/myapp/"
      deploy_repository: "/home/deploy/repos/myapp.git"


    mkdir -p /home/deploy/repos/myapp.git
    git init --bare /home/deploy/repos/myapp.git

    cd projects/myapp
    git remote add production deploy@alpha.example.com:/home/deploy/repos/myapp.git
    git push production master:master

    mkdir -p /home/deploy/webapps/myapp
    cd /home/deploy/webapps/myapp
    git clone /home/deploy/repos/myapp.git ./
    git submodule init && git submodule update
    chmod 777 tmp/ log/
    head -c 200 /dev/urandom | base64 -w 0 > config/.secret_token.txt

