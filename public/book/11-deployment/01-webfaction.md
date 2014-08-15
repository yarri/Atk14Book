Deployment na WebFaction
========================

Oblíbený [webhoster WebFaction](https://www.webfaction.com/) nabízí na svých hostingových serverech rozumné prostředí s ssh přístupem.
Rozjet zde ATK14 aplikaci není žádný velký problém.

Ukážeme vám postup deploymentu, který jsme si oblíbili.

Představte si, že Jan "Kinky" Kučera přistupuje na WebFaction jako uživatel snapper a má za úkol rozjet aplikaci Filcker - revoluční službu
pro sdílení nepříjemných zážitků.

Prvotní nastavení produkčního prostředí
---------------------------------------

Kinky vytvoři v $HOME na webfaction adresář bin, kam nalinkuje binárku php v preferované verzi.

	[kinky@notebook ~]$ ssh snapper@web436.webfaction.com
	[snapper@web436 ~]$ mkdir bin
	[snapper@web436 ~]$ ln -s /usr/local/bin/php53 ~/bin/php

Do .bashrc přidá 2 důležité řádky. Podívejte se, jsou to ty poslední.

	[snapper@web436 ~]$ cat .bashrc
	# .bashrc

	# Source global definitions
	if [ -f /etc/bashrc ]; then
					. /etc/bashrc
	fi

	# User specific aliases and functions
	export ATK14_ENV=production
	export PATH=/home/snapper/bin:$PATH

Parametr prostředí ATK14_ENV je nezbytné nastavit na hodnotu production a do PATH byla doplněna cesta
k nově vzniklému adresáři bin.

Stejné nastavení Kinky doplní i do crontabu pro případ, že by se čase spouštěly automatické procesy. Zatím je však crontab prázdný.

	[snapper@web436 ~]$ crontab -l
	ATK14_ENV=production
	PATH=/home/snapper/bin:$PATH

Repozitář pro deloyment
-----------------------

Aplikace bude deployována pomocí gitu a jeho post-receive háčku. Kinky bytvoří prázdný deployovací repozitář.

	[snapper@web436 ~]$ mkdir -p ~/repos/filcker.git
	[snapper@web436 ~]$ git init --bare ~/repos/filcker.git
	[snapper@web436 ~]$ exit

Na svém notebooku si Kinky přidá nový repozitář jako remote s názvem production a zesynchronizuje ho se svým mastrem.

	[kinky@notebook ~]$ cd projects/filcker
	[kinky@notebook ~/projects/filcker]$ git remote add production snapper@web436.webfaction.com:repos/filcker.git
	[kinky@notebook ~/projects/filcker]$ git push production master

Checkout aplikace do produkce
-----------------------------

Kinky na nic nečeká a začne instalovat aplikaci do produkce do předem připraveného adresáře webapps/filcker

	[kinky@notebook ~]$ ssh snapper@web436.webfaction.com
	[snapper@web436 ~]$ cd webapps/filcker/
	[snapper@web436 ~/webapps/filcker] git clone /home/snapper/repos/filcker.git ./
	[snapper@web436 ~/webapps/filcker] git submodule init && git submodule update
	[snapper@web436 ~/webapps/filcker] chmod 777 tmp log

Aplikace flicker na produkci načítá hodnotu konstanty SECRET\_TOKEN ze souboru config/secret\_token.txt. Tento soubor není verzován a měl
by být dostatečně dlouhý, náhodný a za všech okolností udržován v tajnosti. Kinky může klidně soubor config/secret\_token.txt vytvořit ručně
například tak, že do něj napíše jednu za svých psychedelických básní, ve které pro jistotu zpřeháže řádky a písmena, nebo využije náhodnou posloupnost znaků
z /dev/urandom.

	[snapper@web436 ~/webapps/filcker] head -c 200 /dev/urandom | base64 -w 0 > config/secret_token.txt

Vytvoření produkční databáze
----------------------------

Připojení do databáze (config/database.yml) je nastaveno tak, že produkční heslo závisí na hodnotě SECRET\_TOKEN. Kinky si tedy zjistí heslo do produkční
databáze uživatele i databázi vytvoří v control panelu WebFaction.

	[snapper@web436 ~/webapps/filcker] ./scripts/dump_config database
	Array
	(
			[development] => Array
					(
							[host] => 127.0.0.1
							[database] => filcker_devel
							[username] => filcker_devel
							[password] => KremrolE001
					)

			[test] => Array
					(
							[host] => 127.0.0.1
							[database] => filcker_test
							[username] => filcker_test
							[password] => KremrolE001
					)

			[production] => Array
					(
							[host] => 127.0.0.1
							[database] => filcker_production
							[username] => filcker_production
							[password] => 0ddc684a50abac1e8bc076f174adbcd22494ef0d
					)

	)

Post-receive hook
-----------------

Nyní Kinky vytvoří post-receive hook v deployovacím repozitáři. Tento hook zajistí, že cokoli se objeví v master větvi v deployovacím repozitáři, bude automaticky
nainstalováno do produkce.

	[snapper@web436 ~]$ cat ~/repos/filcker.git/hooks/post-receive
	#!/bin/bash

	export PATH=/home/snapper/bin:$PATH
	export ATK14_ENV=production
	unset GIT_DIR

	cd /home/snapper/webapps/filcker/ &&
	git remote set-url origin /home/snapper/repos/filcker.git &&
	git fetch &&
	git reset --hard origin/master &&
	git submodule init &&
	git submodule update &&
	./scripts/migrate &&
	./scripts/delete_temporary_files &&
	echo ">>> Content has been synchronized"


Kinky se nakonec ujistí, že je post-receive hook skutečně spustitelný.

	[snapper@web436 ~]$ chmod +x ~/repos/flicker.git/hooks/post-receive 

Deployment
----------

Následný deployment je pak snadný jako facka. Kinky a jeho kolegové v klidu vyvíjejí na svých noteboocích a jakmile se rozhodnou svou práci zvěřejnit v produkci,
pushnou konkrétní revizi do master větve v deployovacím repozitáři.

	[kinky@notebook ~/projects/filcker]$ git push production
	Counting objects: 11, done.
	Delta compression using up to 4 threads.
	Compressing objects: 100% (6/6), done.
	Writing objects: 100% (6/6), 494 bytes, done.
	Total 6 (delta 5), reused 0 (delta 0)
	remote: From /home/snapper/repos/flicker
	remote:    d4f1df2..a3098bf  master     -> origin/master
	remote: HEAD is now at a3098bf Better text
	remote: 2014-01-30 10:08:19 migration[15357]: there is nothing to migrate
	remote: 107 temporary files/dirs deleted
	remote: >>> Content has been synchronized
	To snapper@web431.webfaction.com:repos/flicker.git
		 d4f1df2..a3098bf  master -> master

Tohleto vůbec není špatné.
