<?php
/**
 * Script tạo categories cho fashion store
 */

use Magento\Framework\App\Bootstrap;

require_once '/var/www/magento/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();

$categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
$categoryRepository = $objectManager->get('\Magento\Catalog\Api\CategoryRepositoryInterface');

// Cấu trúc danh mục
$categories = [
    [
        'name' => 'Giày Nam',
        'url_key' => 'giay-nam',
        'description' => 'Giày dép nam thời trang, chất lượng cao',
        'children' => [
            ['name' => 'Giày Sneaker Nam', 'url_key' => 'giay-sneaker-nam'],
            ['name' => 'Giày Tây Nam', 'url_key' => 'giay-tay-nam'],
            ['name' => 'Dép Nam', 'url_key' => 'dep-nam']
        ]
    ],
    [
        'name' => 'Giày Nữ',
        'url_key' => 'giay-nu',
        'description' => 'Giày dép nữ thời trang, phong cách hiện đại',
        'children' => [
            ['name' => 'Giày Cao Gót', 'url_key' => 'giay-cao-got'],
            ['name' => 'Giày Sneaker Nữ', 'url_key' => 'giay-sneaker-nu'],
            ['name' => 'Giày Bệt', 'url_key' => 'giay-bet'],
            ['name' => 'Dép Nữ', 'url_key' => 'dep-nu']
        ]
    ],
    [
        'name' => 'Quần Áo Nam',
        'url_key' => 'quan-ao-nam',
        'description' => 'Thời trang nam, phong cách và chất lượng',
        'children' => [
            ['name' => 'Áo Sơ Mi Nam', 'url_key' => 'ao-so-mi-nam'],
            ['name' => 'Áo Thun Nam', 'url_key' => 'ao-thun-nam'],
            ['name' => 'Quần Jeans Nam', 'url_key' => 'quan-jeans-nam'],
            ['name' => 'Quần Kaki Nam', 'url_key' => 'quan-kaki-nam']
        ]
    ],
    [
        'name' => 'Quần Áo Nữ',
        'url_key' => 'quan-ao-nu',
        'description' => 'Thời trang nữ, phong cách trẻ trung',
        'children' => [
            ['name' => 'Váy Nữ', 'url_key' => 'vay-nu'],
            ['name' => 'Áo Sơ Mi Nữ', 'url_key' => 'ao-so-mi-nu'],
            ['name' => 'Áo Thun Nữ', 'url_key' => 'ao-thun-nu'],
            ['name' => 'Quần Jeans Nữ', 'url_key' => 'quan-jeans-nu']
        ]
    ]
];

function createCategory($categoryData, $parentId = 2) {
    global $categoryFactory, $categoryRepository;
    
    $category = $categoryFactory->create();
    $category->setName($categoryData['name'])
             ->setUrlKey($categoryData['url_key'])
             ->setIsActive(true)
             ->setIncludeInMenu(true)
             ->setParentId($parentId)
             ->setStoreId(0);
             
    if (isset($categoryData['description'])) {
        $category->setDescription($categoryData['description']);
    }
    
    try {
        $category = $categoryRepository->save($category);
        echo "Đã tạo category: " . $categoryData['name'] . " (ID: " . $category->getId() . ")\n";
        
        // Tạo subcategories
        if (isset($categoryData['children'])) {
            foreach ($categoryData['children'] as $childData) {
                createCategory($childData, $category->getId());
            }
        }
        
        return $category->getId();
    } catch (Exception $e) {
        echo "Lỗi khi tạo category " . $categoryData['name'] . ": " . $e->getMessage() . "\n";
    }
}

// Tạo tất cả categories
foreach ($categories as $categoryData) {
    createCategory($categoryData);
}

echo "Hoàn thành tạo categories!\n";
?>