<?php
$xml = simplexml_load_file('playlist.xml');

// Dodavanje pjesme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dodaj'])) {
    dodajPjesmu($xml, $_POST['izvodac'], $_POST['naziv'], $_POST['album'], $_POST['trajanje']);
    spremiXML($xml, 'playlist.xml');
}

// Brisanje pjesme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['obrisi'])) {
    obrisiPjesmu($xml, $_POST['pjesmaBrisanje']);
    spremiXML($xml, 'playlist.xml');
}

function dodajPjesmu(&$xml, $izvodac, $naziv, $album, $trajanje) {
    $pjesma = $xml->addChild('pjesma');
    $pjesma->addChild('izvodac', $izvodac);
    $pjesma->addChild('naziv', $naziv);
    $pjesma->addChild('album', $album);
    $pjesma->addChild('trajanje', $trajanje);
}


function obrisiPjesmu($xml, $naziv) {
    $lowercaseNaziv = strtolower(trim($naziv));

    foreach ($xml->pjesma as $pjesma) {
        $pjesmaNaziv = trim((string)$pjesma->naziv);

        if (strcasecmp($pjesmaNaziv, $lowercaseNaziv) === 0) {
            $dom = dom_import_simplexml($pjesma);
            $dom->parentNode->removeChild($dom);
            break;
        }
    }
}

function spremiXML($xml, $filename) {
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());
    $dom->save($filename);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Playlist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  
    <style>
        header {
            background-color: #ccc;
        }

        .naslov {
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <header>
        <div class="naslov">
            <h1>Playlist</h1>
        </div>
    </header>

    <main>
        <table class="table">
            <thead>
                <tr>
                    <th>Izvođač</th>
                    <th>Naziv</th>
                    <th>Album</th>
                    <th>Trajanje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($xml->pjesma as $indeks => $pjesma): ?>
                    <tr>
                        <td><?php echo $pjesma->izvodac; ?></td>
                        <td><?php echo $pjesma->naziv; ?></td>
                        <td><?php echo $pjesma->album; ?></td>
                        <td><?php echo $pjesma->trajanje; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h2>Dodaj pjesmu</h2>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="izvodac" class="form-label">Izvođač</label>
                <input type="text" class="form-control" id="izvodac" name="izvodac" required>
            </div>
            <div class="mb-3">
                <label for="naziv" class="form-label">Naziv</label>
                <input type="text" class="form-control" id="naziv" name="naziv" required>
            </div>
            <div class="mb-3">
                <label for="album" class="form-label">Album</label>
                <input type="text" class="form-control" id="album" name="album" required>
            </div>
            <div class="mb-3">
                <label for="trajanje" class="form-label">Trajanje</label>
                <input type="text" class="form-control" id="trajanje" name="trajanje" required>
            </div>
            <button type="submit" class="btn btn-primary" name="dodaj">Dodaj</button>
        </form>
        
        <h2>Obriši pjesmu</h2>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="indeks" class="form-label">Ime pjesme</label>
                <input type="text" class="form-control" id="indeks" name="pjesmaBrisanje" required>
            </div>
            <button type="submit" class="btn btn-danger" name="obrisi">Obriši</button>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
