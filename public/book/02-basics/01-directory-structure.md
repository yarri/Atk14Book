Adresářová struktura
====================

Takto vypadá ATK14 aplikace na disku.

    app/
        controllers/            místo pro kontrolery
        fields/                 místo pro políčka formulářů
        forms/                  místo pro formuláře
        helpers/                místo pro pomocné funkce zobrazování dat
        layouts/                místo pro layoutové šablony
        models/                 místo pro modely
        views/                  místo pro šablony
            shared/             sdílené šablony
        widgets/                místo pro vzhledové třídy formulářových políček
    atk14/                      zdrojové kódy frameworku
    config/                     místo pro konfigurační soubory
        routers/                zde jsou routery pro sexy URL
    db/migrations/              místo pro databázové migrace
    lib/                        místo pro externí knihovny
    locale/                     místo pro lokalizační slovníky gettextu
    log/                        místo pro aplikační logy
    public/                     místo pro stylesheets, javascripty, obrázky a další statický obsah
    robots/                     zde sídlí roboti
        lock/                   zde si roboti vytvářejí zámky pro zamezení konkurenčního běhu
    scripts/                    shellové skripty pro ovládání aplikace
    test/                       místo pro testovací třídy
        controllers/            testy pro kontrolery
        fields/                 testy pro formulářová políčka
        fixtures/               testovací data
        models/                 testy pro modely
    tmp/                        místo pro dočasné soubory

Na první pohled se těch adresářů zdá hodně. Nejedná se o klam &mdash; adresářů je skutečně hodně :) Brzy ale zjistíte, že nejvíce času budete trávít ve čtyřech adresářích.

Komplexní aplikace zpravidla obsahuje i několik tzv. namespaces. Jedná se o jakési podaplikace, které s hlavní aplikací sdílí databázi, modely, sdílené šablony, formulářová políčka a zobrazovací helpery.
Typický namespace je např. _admin_ &mdash; do adminu se přihlašuje administrátor a provádí zde správu. Když vytvoříte namespace admin ve své aplikaci, vzniknou další adresáře.

    app/controllers/admin/
    app/forms/admin/
    app/views/admin/
    test/controllers/admin/

Dalším oblíbeným namespacem je _api_, kam se umísťují funkce pro datovou komunikaci s okolním světem.
