<?php
// app/Services/CloudinaryService.php (Novo Arquivo)

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

class CloudinaryService {
    private Cloudinary $cloudinary;

    public function __construct()
    {
        $cloudinaryUrl = config('services.cloudinary.url');

        if (empty($cloudinaryUrl)) {
            throw new RuntimeException('A variável CLOUDINARY_URL não está configurada.');
        }

        $this->cloudinary = new Cloudinary($cloudinaryUrl);
    }

    public function uploadImagem(UploadedFile $arquivo, string $pasta): string
    {
        $resultado = $this->cloudinary->uploadApi()->upload($arquivo->getRealPath(), [
            'folder' => $pasta,
            'resource_type' => 'image',
        ]);

        return $resultado['secure_url'];
    }

    public function uploadPdf(UploadedFile $arquivo, string $tituloObra, string $pasta = 'livros/pdfs'): string
    {
        $resultado = $this->cloudinary->uploadApi()->upload($arquivo->getRealPath(), [
            'folder' => $pasta,
            'resource_type' => 'auto',
        ]);

        $urlOriginal = $resultado['secure_url'];
        $nomeDoArquivo = Str::slug($tituloObra) ?: 'obra';

        return str_replace('/upload/', '/upload/fl_attachment:' . $nomeDoArquivo . '/', $urlOriginal);
    }
}