<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$db = '';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);

// $sql = "INSERT INTO categories (category_name, parent_id, parent_name, created_at)
// VALUES ('chiled', 1, 'parent', '$time')";

function conn()
{
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $db = '';
    $conn = new mysqli($servername, $username, $password, $db);
    return $conn;
}

$file = file_get_contents('./document.json');

$arr = json_decode($file, true);

$inner = $arr['w:document']['w:body']['w:p'];

var_dump($inner[1]['w:r']);

foreach ($inner as $key => $value) {
    var_dump($value['w:r']);
}

foreach ($inner as $key => $value) {
    $wpStyle = $value['w:pPr']['w:pStyle']['@w:val'];
    $time = time();

    switch ($wpStyle) {
        case 1:
            $sql = "INSERT INTO categories (wpStyle, created_at) VALUES ('$wpStyle', '$time')";
            conn()->query($sql);
            break;

        case 15:
            $sql =
                'SELECT id, category_name FROM categories WHERE wpStyle = 1 ORDER BY id DESC LIMIT 1';

            $result = conn()
                ->query($sql)
                ->fetch_assoc();

            $parent_id = $result['id'];
            $parent_name = $result['category_name'];

            $sql = "INSERT INTO categories (parent_id, parent_name, wpStyle, created_at)
                    VALUES ('$parent_id', '$parent_name', '$wpStyle', '$time')";
            conn()->query($sql);
            break;

        case 2:
            $sql =
                'SELECT id, category_name FROM categories WHERE wpStyle = 1 OR wpStyle = 15 ORDER BY id DESC LIMIT 1';

            $result = conn()
                ->query($sql)
                ->fetch_assoc();

            $parent_id = $result['id'];
            $parent_name = $result['category_name'];

            $sql = "INSERT INTO categories (parent_id, parent_name, wpStyle, created_at)
                    VALUES ('$parent_id', '$parent_name', '$wpStyle', '$time')";
            conn()->query($sql);
            break;

        case 3:
            $sql =
                'SELECT id, category_name FROM categories WHERE wpStyle = 2 ORDER BY id DESC LIMIT 1';

            $result = conn()
                ->query($sql)
                ->fetch_assoc();

            $parent_id = $result['id'];
            $parent_name = $result['category_name'];

            $sql = "INSERT INTO categories (parent_id, parent_name, wpStyle, created_at)
                    VALUES ('$parent_id', '$parent_name', '$wpStyle', '$time')";
            conn()->query($sql);
            break;

        case 4:
            $sql =
                'SELECT id, category_name FROM categories WHERE wpStyle = 3 ORDER BY id DESC LIMIT 1';

            $result = conn()
                ->query($sql)
                ->fetch_assoc();

            $parent_id = $result['id'];
            $parent_name = $result['category_name'];

            $sql = "INSERT INTO categories (parent_id, parent_name, wpStyle, created_at)
                    VALUES ('$parent_id', '$parent_name', '$wpStyle', '$time')";
            conn()->query($sql);
            break;

        case 5:
            $sql =
                'SELECT id, category_name FROM categories WHERE wpStyle = 4 ORDER BY id DESC LIMIT 1';

            $result = conn()
                ->query($sql)
                ->fetch_assoc();

            $parent_id = $result['id'];
            $parent_name = $result['category_name'];

            $sql = "INSERT INTO categories (parent_id, parent_name, wpStyle, created_at)
                    VALUES ('$parent_id', '$parent_name', '$wpStyle', '$time')";
            conn()->query($sql);
            break;

        case 'a':
            if (array_key_exists('w:r', $value)) {
                $wr = $value['w:r'];

                if (array_key_exists('w:rPr', $wr[0])) {
                    $wrPr = $wr[0]['w:rPr'];

                    if (array_key_exists('w:rStyle', $wrPr)) {
                        $wrStyle = $wrPr['w:rStyle'];

                        if ($wrStyle['@w:val'] == 'a2') {
                            $sql = "SELECT id, category_name FROM categories
                                    WHERE wpStyle = 1 OR wpStyle = 15 OR wpStyle = 2 OR wpStyle = 3 OR wpStyle = 4 OR wpStyle = 5
                                    ORDER BY id DESC LIMIT 1";

                            $result = conn()
                                ->query($sql)
                                ->fetch_assoc();

                            $parent_id = $result['id'];
                            $parent_name = $result['category_name'];

                            $sql = "INSERT INTO articles (parent_cat_id, parent_cat, created_at)
                                    VALUES ('$parent_id', '$parent_name', '$time')";
                            conn()->query($sql);
                        }
                    }
                }
            }
            break;

        default:
            echo $wpStyle;
            break;
    }

    if (array_key_exists('w:r', $value)) {
        $wr = $value['w:r'];

        foreach ($wr as $_key => $_value) {
            if (array_key_exists('w:rPr', $_value)) {
                $wrPr = $_value['w:rPr'];

                if (array_key_exists('w:rStyle', $wrPr)) {
                    if (
                        $wpStyle == 'a' &&
                        ($wrPr['w:rStyle']['@w:val'] == 'a2' ||
                            $wrPr['w:rStyle']['@w:val'] == 'a3' ||
                            $wrPr['w:rStyle']['@w:val'] == 'a4')
                    ) {
                        $sql =
                            'SELECT title, question FROM articles ORDER BY id DESC LIMIT 1';

                        $result = conn()
                            ->query($sql)
                            ->fetch_assoc();

                        if (is_null($result['title'])) {
                            $title = '';
                        } else {
                            $title = $result['title'];
                        }

                        $wt = $_value['w:t'];
                        if (is_array($wt)) {
                            $title = $title . $wt['$'];
                        } else {
                            $title = $title . $wt;
                        }

                        $sql = "UPDATE `articles` SET `title`='$title',`question`='$title' ORDER BY id DESC LIMIT 1";

                        conn()->query($sql);
                    } else {
                        $sql =
                            'SELECT id, reply FROM articles ORDER BY id DESC LIMIT 1';

                        $result = conn()
                            ->query($sql)
                            ->fetch_assoc();

                        if (is_null($result['reply'])) {
                            $reply = '';
                        } else {
                            $reply = $result['reply'];
                        }

                        $wt = $_value['w:t'];
                        if (is_array($wt)) {
                            $reply = $reply . $wt['$'];
                        } else {
                            $reply = $reply . $wt;
                        }

                        $sql = "UPDATE articles
                            SET reply = '$reply' ORDER BY id DESC LIMIT 1";
                        conn()->query($sql);
                    }
                } else {
                    if ($wpStyle == 'a') {
                        $sql =
                            'SELECT id, reply FROM articles ORDER BY id DESC LIMIT 1';

                        $result = conn()
                            ->query($sql)
                            ->fetch_assoc();

                        if (is_null($result['reply'])) {
                            $reply = '';
                        } else {
                            $reply = $result['reply'];
                        }

                        $wt = $_value['w:t'];
                        if (is_array($wt)) {
                            $reply = $reply . $wt['$'];
                        } else {
                            $reply = $reply . $wt;
                        }

                        $sql = "UPDATE articles
                            SET reply = '$reply' ORDER BY id DESC LIMIT 1";
                        conn()->query($sql);
                    } else {
                        $sql =
                            'SELECT category_name, id FROM categories ORDER BY id DESC LIMIT 1';

                        $result = conn()
                            ->query($sql)
                            ->fetch_assoc();

                        if (is_null($result['category_name'])) {
                            $name = '';
                        } else {
                            $name = $result['category_name'];
                        }

                        $wt = $_value['w:t'];
                        if (is_array($wt)) {
                            $name = $name . $wt['$'];
                        } else {
                            $name = $name . $wt;
                        }

                        $sql = "UPDATE categories
                            SET category_name = '$name' ORDER BY id DESC LIMIT 1";
                        conn()->query($sql);
                    }
                }
            } else {
                if ($wpStyle == 'a') {
                    $sql =
                        'SELECT id, reply FROM articles ORDER BY id DESC LIMIT 1';

                    $result = conn()
                        ->query($sql)
                        ->fetch_assoc();

                    if (is_null($result['reply'])) {
                        $reply = '';
                    } else {
                        $reply = $result['reply'];
                    }

                    $wt = $_value['w:t'];
                    if (is_array($wt)) {
                        $reply = $reply . $wt['$'];
                    } else {
                        $reply = $reply . $wt;
                    }

                    $sql = "UPDATE articles
                            SET reply = '$reply' ORDER BY id DESC LIMIT 1";
                    conn()->query($sql);
                } else {
                    $sql =
                        'SELECT category_name, id FROM categories ORDER BY id DESC LIMIT 1';

                    $result = conn()
                        ->query($sql)
                        ->fetch_assoc();

                    if (is_null($result['category_name'])) {
                        $name = '';
                    } else {
                        $name = $result['category_name'];
                    }

                    $wt = $_value['w:t'];
                    if (is_array($wt)) {
                        $name = $name . $wt['$'];
                    } else {
                        $name = $name . $wt;
                    }

                    $sql = "UPDATE categories
                            SET category_name = '$name' ORDER BY id DESC LIMIT 1";
                    conn()->query($sql);
                }
            }
        }
    }
}
