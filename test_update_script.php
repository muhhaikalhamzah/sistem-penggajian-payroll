<?php
$dir = __DIR__ . '/resources/views';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        
        // Replacement array
        $replacements = [
            '<th>Action</th>' => '<th>Aksi</th>',
            '<th>Actions</th>' => '<th>Aksi</th>',
            '>Action<' => '>Aksi<',
            '>Actions<' => '>Aksi<',
            'Cancel</a>' => 'Batal</a>',
            'Generate</button>' => 'Buat</button>',
            'Batch Generate Payslips' => 'Pembuatan Slip Gaji',
            'Enter the period (e.g. <code>10-2023</code>) to generate payslips for all active employees.' => 'Masukkan periode (contoh <code>10-2023</code>) untuk membuat slip gaji bagi semua pegawai aktif.',
            'Period</label>' => 'Periode</label>',
            'Generate Payslips' => 'Buat Slip Gaji',
            '>Approve<' => '>Setujui<',
            'title="Approve"' => 'title="Setujui"',
            '>Reject<' => '>Tolak<',
            'title="Reject"' => 'title="Tolak"'
        ];
        
        $newContent = str_replace(array_keys($replacements), array_values($replacements), $content);
        
        if ($newContent !== $content) {
            file_put_contents($file->getPathname(), $newContent);
            echo "Updated: " . $file->getPathname() . "\n";
        }
    }
}
