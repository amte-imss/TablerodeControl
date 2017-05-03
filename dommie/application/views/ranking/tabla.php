<h3><?php echo $titulo; ?></h3>
<table  class="table table-bordered">
    <thead>
    <th>Unidad / UMAE</th>
    <th>#</th>
</thead>
<tbody>
    <?php
    foreach ($datos as $row)
    {
        ?>
        <tr>
            <td><?php echo $row['unidad']; ?></td>
            <td><?php echo number_format($row['cantidad'], 2); ?></td>
        </tr>
        <?php
    }
    ?>
</tbody>
</table>