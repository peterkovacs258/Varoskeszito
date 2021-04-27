<?php
require APPROOT . '/views/includes/head.php';
?>

<div class="container">
    <div class="leftSide">
        <div id="megyeDiv">
            <span>Megye</span>
            <select id="megyeSelect" class="form-select form-select-lg mb-3" >
                <option value="" disabled selected>Kérem válasszon!</option>
                <?php echo $data['megyekOptionba']; ?>
            </select>
        </div>
        <div id="ujVarosDiv">
            <span>Új város </span>
            <input type="text" placeholder="Például Kecskemét" id="varosInput" class="form-control">
            <input type="button" value="Felvesz" class="btn btn-light btnFelveszVaros">
        </div>
    </div>
    <div class="rightSide">
    </div>
</div>