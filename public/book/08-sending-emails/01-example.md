Základní ukázka zasílání e-mailu
================================

Na <http://www.atk14.net/en/reminders/create_new/> naleznete hypotetický příklad se zasíláním e-mailů. Přísná knihovnice zde zasílá roztržitým čtenářům upomínky k vrácení knih do knihovny. Příklad si zde projdeme.

Je zde jednoduchý formulář pro zadání e-mailové adresy čtenáře a výběr knihy.

[include file=app/forms/reminders/create_new_form.php]

V kontroleru na tom správném místě voláme metodu ```$this->mailer->send_reminder()```, které předáme příslušné parametry.

[include file=app/controllers/reminders_controller.php]

V maileru je akce ```send_reminder()``` správně definovaná.

[include file=app/controllers/application_mailer.php]

Text e-mailu je vyrenderován ze šablony.

[include file=app/views/mailer/send_reminder.tpl]

Vidíte, že zasílání zpráv v ATK14 není velká věda.
