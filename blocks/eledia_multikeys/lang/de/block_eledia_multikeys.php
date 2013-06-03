<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 *
 * @package block
 * @category eledia_multikeys
 * @copyright 2013 eLeDia GmbH {@link http://www.eledia.de}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['back'] = 'Zurück';

$string['choose_count'] = 'Anzahl Kurscodes';
$string['choose_course'] = 'Kurs wählen';
$string['choose_length'] = 'Länge der Kurscodes (Anzahl Zeichen)';
$string['choose_mail'] = 'E-Mail (Empfänger)';
$string['course_notuses_multikeys'] = 'Der angegebene Kurs verwendet nicht das Einschreibeverfahren \"Kurscode Einschreibung\"';

$string['eledia_multikeys:addinstance'] = 'Füge Kursschlüssel Block hinzu';
$string['eledia_multikeys:view'] = 'Verwende Kursschlüssel Block';
$string['email_send'] = 'Die Kurscodes wurden an die angegebene E-Mail-Adresse versandt';
$string['email_enrolkeys_subject'] = 'Ihre Kurscodes';
$string['email_enrolkeys_message'] = 'Guten Tag {$a->firstname},

für Sie wurden Kurscodes für einen Kurs im Moodle System \'{$a->sitename}\' generiert.

Die für Sie generierten Kurscodes lauten:

{$a->keylist}

Bitte beachten Sie, dass jeder dieser Kurscodes nur einmal zum Einschreiben verwendet werden kann.

Mit diesen Kurscodes können sich Ihre Teilnehmer nach einer Registrierung auf folgender Seite in den Kurs {$a->course} einschreiben:

{$a->link}

In den meisten E-Mail-Programmen ist dieser Link aktiv, so dass Sie ihn einfach anklicken können. Wenn dies nicht funktioniert, kopieren Sie bitte die komplette Adresse in die obere Zeile des Browser-Fensters.

Bei Problemen wenden Sie sich bitte an die Administrator/innen der Website.

Viel Erfolg!
{$a->admin}';

$string['generate_keys'] = 'Generiere Kurscodes';
$string['generate_header'] = 'Parameter für die Erzeugung der Kurscodes';

$string['keys_header'] = 'Kurscodes erstellen';
$string['keytoolong'] = 'Schlüssel dürfen mit prefix maximal 99 Zeichen lang sein.';

$string['missingcourse'] = 'Kurs fehlt';
$string['missingcount'] = 'Anzahl der Kurscodes fehlt';
$string['missingmail'] = 'E-Mail Adresse fehlt';

$string['pluginname'] = 'eLeDia Schlüsseleinschreibung';
$string['prefix'] = 'Der Kurscode-Präfix';

$string['title'] = 'Einschreibung mit Kurscode';

$string['wrong_mail_format'] = 'Die von Ihnen angegebene E-Mail Adresse ist keine gültige E-Mail-Adresse';
$string['wrong_number'] = 'Falscher Zahlwert';

