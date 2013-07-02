
<div >

    
    <form action="<?php echo URL::basepath().'libreoffice/generatepdf'; ?>" name="frmconverter" method="post" enctype="multipart/form-data" >       
    <select name='asset_file_type'>
        <option value=''>Select File Type</option>
        <?php foreach($this->values['filetypes'] as $key=>$value): ?>
        <option value='<?php echo $key; ?>'><?php echo $value; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="file" name="umrcfile"/>
    <input type="submit" value="Convert" name='convert_to_pdf'/>
</form>

</div>