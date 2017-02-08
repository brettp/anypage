<?php
/**
 * Anypage language
 */

return array(
	'admin:appearance:anypage' => 'Sivut',
	'admin:appearance:anypage:new' => 'Uusi sivu',
	'item:object:anypage' => 'Infosivut',

	'anypage:warning' => 'Varoitus',
	'anypage:unsupported_page_handler_character' => "Syötit virheellisen polun. Polun ensimmäinen osa (ennen toista / -merkkiä) voi sisältää vain kirjaimia a-z, numeroita sekä - ja _ -merkkejä.",

	'anypage:page_handler_conflict' => 'Syöttämäsi polku sisältää termejä, jotka on varattu järjestelmän sisäiseen käyttöön. Tämän polun käyttäminen saattaa aiheuttaa virheitä, joten käytä sitä vain jos tiedät, mitä olet tekemässä!',

	'anypage:anypage_conflict' => 'Tämä polku on jo käytössä sivulla "%s". Vaihda kyseisen sivun polku, mikäli haluat liittää polun tähän sivuun.',

	'anypage:new' => 'Uusi sivu',
	'anypage:no_pages' => 'Yhtäkään sivua ei ole vielä luotu. Klikkaa "Uusi sivu" luodaksesi uuden sivun.',

	'anypage:needs_upgrade' => 'Sivut-työkalu pitää päivittää',
	'anypage:needs_upgrade_body' => 'Sivut-työkalu pitää päivittää. ',
	'anypage:upgrade_now' => 'Päivitä nyt.',
	'anypage:upgrade_success' => 'Päivitettiin AnyPages-plugin',

	// form
	'anypage:path' => 'Sivun polku',
	'anypage:path_full_link' => 'Kokonainen osoite',
	'anypage:view_info' => 'Sivun näyttämiseen käytetään järjestelmän sisäistä näkymää:',
	'anypage:body' => 'Sivun sisältö',
	'anypage:visible_through_walled_garden' => 'Nähtävissä, kun Walled Garden -ominaisuus on päällä',
	'anypage:walled_garden_disabled' => 'Walled Garden ei ole käytössä',
	'anypage:requires_login' => 'Näytä vain kirjautuneille',
	'anypage:show_in_footer' => 'Lisää linkki sivuston footeriin',
	'anypage:layout' => 'Layout',

	'anypage:use_view' => 'Käytä järjestelmän sisäistä näkymää',
	'anypage:use_editor' => 'Kirjoita sivu itse',
	'anypage:use_composer' => 'Käytä composer-työkalua sivun rakentaaksesi',

	// actions
	'anypage:save:success' => 'Sivu tallennettu',
	'anypage:delete:success' => 'Sivu poistettu',
	'anypage:no_path' => 'Syötä sivulle polku',
	'anypage:no_view' => 'Sinun pitää syöttää käytettävä näkymä.',
	'anypage:no_description_or_view' => 'Syötä sivulle sisältö tai valitse vaihtoehto "Käytä näkymää".',
	'anypage:any_page_handler_conflict' => 'Syöttämäsi polku on jo käytössä.',
	'anypage:delete:failed' => 'Sivun poistaminen epäonnistui.',

	// example pages
	'anypage:example:title' => 'Esimerkkisivu',
	'anypage:example_page:description' => 'Tämä on esimerkkisivu!',

	'anypage:example:view:title' => 'Esimerkkisivu (joka käyttää näkymää)',
	'anypage:test_page_view' => 'Tämä on esimerkisivu, joka käyttää järjestelmän sisäistä näkymää!',

	'anypage:activate:admin_notice' => 'AnyPage-plugin on lisännyt esimerkkisivuja. Siirry osoitteeseen <a href="%s">admin interface</a> lisätäksesi uusia sivuja.',
);
