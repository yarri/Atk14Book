Prvotní instalace aplikace do produkce
======================================

Poté, co jsme nádherně popsali produkční instalaci do souboru config/deploy.yml, nás čeká instalace aplikace do produkčního prostředí. Jedná se o souvislou řadu úkonů, se kterými nám pomůže skript *initialize_deployment_stage*.

Jeho použití je následující:


		./scripts/initialize_deployment_stage production

Klidně to vyzkoušejte. Nestane se nic, pouze se vypíší shellové příkazy, které nainstalují aplikaci do dané produkčního prostředí.

Prozkoumejte je, a pokud se vám budou zamlouvat, spusťtě:

		./scripts/initialize_deployment_stage production | sh

Pokud všechno dopadne dobře, máte vyhráno. Pokud něco selže, pokuste se zjistit, kde nastal problém, proveďte opravu a pokračujte ve spouštění příkazů od příslušného místa.

Jakmile máte hotovo, přihlaste se na danou produkci.

		./scripts/shell production

... a dokonfigurujte aplikaci. Což znemaná především konfigurace připojení do databáze v souboru local_config/database.yml a event. další specifická nastavení v souboru local_config/settings.php.

