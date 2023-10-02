<table>
    <tr>
        <th>Minute</th>
        <th>Request count</th>
        <th>Average request length</th>
        <th>Minimal request time</th>
        <th>Maximal request time</th>
    </tr>
<?php $count = 0; ?>
<?php foreach($data as $row): ?>
    <tr>
        <td><?=$row->minute?></td>
        <td><?=$row->request_count?></td>
        <td><?=$row->average_length?></td>
        <td><?=$row->min_date?></td>
        <td><?=$row->max_date?></td>
    </tr>
    <?php $count++ ?>
<?php endforeach; ?>
<?php if (!$count): ?>
    <tr>
        <td rowspan="5">Data not found</td>
    </tr>
<?php endif; ?>
</table>
