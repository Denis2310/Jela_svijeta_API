<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Jela Svijeta</title>

    </head>
    <body>    
    	<h2 align="center">Dobrodošli na Jela Svijeta API</h2>
    	<p>Početna ruta za slanje zahtjeva je: localhost:8000/api</p>
    	<h3><strong>Parametri:</strong> </h3>
    	<ul>

    		<li><i>per_page:</i> Broj rezultata po stranici</li><br>
    		<li><i>page:</i> Broj stranice</li><br>
    		<li><i>category:</i> ID kategorije za filtriranje rezultata, '!null' sa kategorijom, 'null' bez kategorije</li><br>
    		<li><i>tags:</i> Lista ID-jeva tagova koje mora sadržavati jelo</li><br>
    		<li><i>diff_time:</i> Cijeli broj (Timestamp). Vraćaju se samo jela kreirana,modificirana, obrisana nakon tog datuma.</li><br>

    		<li>Parametre 'with' i 'lang' nazalost nisam uspio odraditi. Prvi parametar nisam uspio odraditi jer nisam znao kako bih izvrsio filtriranje u Resources\MealCollection datoteci. Drugi parametar nisam uspio odraditi jer se tablice svaki put popunjavaju nasumično pa nisam znao kako bih svaki put odmah pomoću seedera ubacio i prijevode kada su retci u tablici svaki puta drukciji, te koristenjem laravel-translatable paketa stvara se nova tablica te mi se tablica 'meals' isprazni i sadrzi samo 'null' vrijednosti.</li>

    	</ul>
    </body>
</html>

