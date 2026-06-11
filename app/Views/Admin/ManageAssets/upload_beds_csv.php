<body>
    <?php if (session()->getFlashdata('error')): ?>
        <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <p style="color:green;"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>
    <div class="belownav">
        <form action="<?= base_url('Admin/import_beds_csv') ?>" method="post" enctype="multipart/form-data">
            <div class="row">
                 <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <input type="file" name="csv_file" accept=".csv" required>
                </div>
                 <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <button type="submit">Upload & Import</button>
                </div>

            </div>
        </form>
    </div>
</body>