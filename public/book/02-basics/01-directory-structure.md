Adresářová struktura
====================

ATK14 drží soubory na pevně daných místech. Jakmile strukturu jednou pochopíš, budeš se v každém ATK14 projektu okamžitě orientovat — aniž bys musel číst jakoukoliv dokumentaci.

    app/
        controllers/            kontrolery – obsluhují HTTP požadavky
        fields/                 vlastní formulářová políčka
        forms/                  formuláře
        helpers/                pomocné funkce pro zobrazování dat v šablonách
        layouts/                layoutové šablony – definují rozložení komponent ve stránkách
        models/                 modely – přístup k databázi a byznysová logika
        views/                  šablony (Smarty)
            shared/             sdílené šablony použitelné napříč kontrolery
        widgets/                třídy definující vzhled formulářových políček
    atk14/                      zdrojové kódy frameworku (nesahejte sem)
    config/                     konfigurační soubory
        routers/                routery pro definici URL schématu
    db/migrations/              databázové migrace
    lib/                        sdílené knihovny specifické pro tvůj projekt
    local_config/               lokální konfigurace, která se neverzuje v Gitu
    local_scripts/              shellové skripty specifické pro tuto konkrétní instalaci
    locale/                     lokalizační slovníky (gettext)
    log/                        aplikační logy
    public/                     soubory dostupné z webu: CSS, JS, obrázky a další statický obsah
    robots/                     roboti – skripty pro periodické nebo na pozadí běžící úlohy
        lock/                   zámky zabraňující souběžnému spuštění stejných robotů
    scripts/                    shellové skripty pro správu aplikace
    test/                       testy
        app/                    základní aplikační testy
        controllers/            testy kontrolerů
        fields/                 testy formulářových políček
        fixtures/               sady testovacích dat
        helpers/                testy zobrazovacích helperů
        lib/                    testy sdílených knihoven
        models/                 testy modelů
        routers/                testy routerů
    tmp/                        dočasné soubory (např. cache)
    vendor/                     knihovny nainstalované přes Composer

Na první pohled se těch adresářů zdá hodně — a je to pravda :) V praxi ale zjistíš, že drtivou většinu času trávíš jen ve čtyřech z nich: `app/controllers`, `app/views`, `app/models` a `app/forms`.

Namespaces
----------

Větší aplikace bývají rozděleny do tzv. **namespaces** — podaplikací, které sdílejí databázi, modely, sdílené šablony, formulářová políčka a helpery s hlavní aplikací, ale mají vlastní kontrolery a pohledy.

Typickým příkladem je namespace `admin`, kde má správce k dispozici nástroje pro administraci. Namespace vytvoříš jednoduše přidáním podadresářů:

    app/controllers/admin/
    app/forms/admin/
    app/views/admin/
    test/controllers/admin/

Dalším běžným namespacem je `api`, do kterého se umísťují endpointy pro strojovou komunikaci s okolním světem.

Důležité konfigurační soubory
-----------------------------

    config/
        settings.php            hlavní konfigurační soubor aplikace
        locale.yml              seznam podporovaných jazyků
        deploy.yml              nastavení pro nasazování aplikace do produkce
        database.yml            přihlašovací údaje k databázi

Pokud bude konfigurační soubor se stejným jménem umístěn v adresáři `local_config`, bude načten přednostně. Typicky se v produkci do `local_config/database.yml` uloží připojení do ostré databáze.
