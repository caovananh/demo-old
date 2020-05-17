<?php
namespace App\Models;
use CodeIgniter\Model;
class Product extends Model {
    protected $table = 'product';
    protected $useTimestamps = false;
    protected $allowedFields = ['name', 'price'];

}