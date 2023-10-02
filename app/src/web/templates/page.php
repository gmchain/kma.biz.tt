<html>

<body>
    <h2>Maria stats:</h2>
    <?php

    $data = $mariaData;
    include "table.php";

    ?>
    <h2>Clickhouse stats:</h2>
    <?php

    $data = $clickHouseData;
    include "table.php";

    ?>
</body>

</html>