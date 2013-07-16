<table class="" cellpadding="4" cellspacing="1" border="0">
    <thead>
                <th>EMP ID# </th>
                <th>Employee Name </th>
                <th>Employee Type</th>
                <th>Employee Birthday </th>
                <th>Marital Status </th>
                <th> City</th>
                <th>Country Code. </th>
                <th>Mobile No </th>
                <th>Email</th>
                  <th>Address</th>
    </thead>
    <tbody>
           <?php
           foreach($this->values['users'] as $rowemp):
               $rowtype = ($rowemp['emp_number'] % 2 ==0) ? 'odd' : 'even';
               ?>
                <tr class="<?php echo $rowtype; ?>">
                    <td><?php echo $rowemp['employee_id']; ?></td>
                    <td><?php echo $rowemp['emp_firstname'].' '.$rowemp['emp_lastname']; ?></td>
                    <td><?php echo $rowemp['emp_type']; ?></td>
                    <td><?php echo $rowemp['emp_birthday']; ?></td>
                    <td><?php echo $rowemp['emp_marital_status']; ?></td>
                    <td><?php echo $rowemp['city_code']; ?></td>
                    <td><?php echo $rowemp['coun_code']; ?></td>
                    <td><?php echo $rowemp['emp_mobile']; ?></td>
                    <td><?php echo $rowemp['emp_work_email']; ?></td>
                   <td><?php echo $rowemp['emp_perm_addr']; ?></td>
                <?php

                endforeach;
                ?>
    </tbody>
</table>
<style type="text/css">
    thead{
        background:#4B93D4;
        color:#f7f7f7;
       cursor:pointer;

    }
    table {
        border:1px solid #ccc;
    }
    .odd {
           background:#FAFAFA;
    }
</style>