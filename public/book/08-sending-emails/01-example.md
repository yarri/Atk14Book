Základní ukázka zasílání e-mailu
================================

Na <http://www.atk14.net/en/reminders/create_new/> naleznete hypotetický příklad se zasíláním e-mailů. Přísná knihovnice zde zasílá roztržitým čtenářům upomínky k vrácení knih do knihovny. Příklad si zde projdeme.

Je zde jednoduchý formulář pro zadání e-mailové adresy čtenáře a výběr knihy.

[Include app/forms/reminders/create_new_form.php]

V kontroleru na tom správném místě voláme metodu ```$this->mailer->send_reminder()```, které předáme příslušné parametry.

[Include app/controllers/reminders_controller.php]

V maileru je akce ```send_reminder()``` správně definovaná.

[Include app/controllers/application_mailer.php]

Text e-mailu je vyrenderován ze šablony.

[Include app/views/mailer/send_reminder.tpl]

Vidíte, že zasílání zpráv v ATK14 není velká věda.
