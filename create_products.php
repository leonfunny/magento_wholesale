<?php
/**
 * Script tạo sản phẩm mẫu cho fashion store
 */

use Magento\Framework\App\Bootstrap;

require_once '/var/www/magento/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();

$productFactory = $objectManager->get('\Magento\Catalog\Model\ProductFactory');
$productRepository = $objectManager->get('\Magento\Catalog\Api\ProductRepositoryInterface');
$categoryLinkManagement = $objectManager->get('\Magento\Catalog\Api\CategoryLinkManagementInterface');

// Sản phẩm mẫu
$products = [
    [
        'sku' => 'SNEAKER-NAM-001',
        'name' => 'Giày Sneaker Nam Trắng Classic',
        'description' => 'Giày sneaker nam màu trắng, phong cách cổ điển, chất liệu cao cấp. Thiết kế thoải mái cho việc đi bộ hàng ngày.',
        'short_description' => 'Sneaker nam trắng classic, thoải mái và phong cách',
        'price' => 1500000,
        'weight' => 0.8,
        'category_ids' => [4], // Giày Sneaker Nam
        'attribute_set_id' => 4
    ],
    [
        'sku' => 'GIAY-CAO-GOT-001',
        'name' => 'Giày Cao Gót Nữ Đen Thanh Lịch',
        'description' => 'Giày cao gót nữ màu đen, cao 7cm, thiết kế thanh lịch phù hợp cho công sở và dự tiệc. Chất liệu da thật.',
        'short_description' => 'Cao gót nữ đen thanh lịch, cao 7cm',
        'price' => 2200000,
        'weight' => 0.6,
        'category_ids' => [8], // Giày Cao Gót
        'attribute_set_id' => 4
    ],
    [
        'sku' => 'AO-SO-MI-NAM-001',
        'name' => 'Áo Sơ Mi Nam Trắng Công Sở',
        'description' => 'Áo sơ mi nam màu trắng, chất liệu cotton cao cấp, thiết kế formal phù hợp cho môi trường công sở.',
        'short_description' => 'Sơ mi nam trắng công sở, cotton cao cấp',
        'price' => 450000,
        'weight' => 0.3,
        'category_ids' => [13], // Áo Sơ Mi Nam
        'attribute_set_id' => 4
    ],
    [
        'sku' => 'VAY-NU-001',
        'name' => 'Váy Nữ Hoa Nhí Vintage',
        'description' => 'Váy nữ họa tiết hoa nhí, phong cách vintage, chất liệu voan mềm mại. Thiết kế nữ tính và duyên dáng.',
        'short_description' => 'Váy nữ hoa nhí vintage, voan mềm mại',
        'price' => 780000,
        'weight' => 0.4,
        'category_ids' => [18], // Váy Nữ
        'attribute_set_id' => 4
    ],
    [
        'sku' => 'QUAN-JEANS-NAM-001',
        'name' => 'Quần Jeans Nam Xanh Đậm Slim Fit',
        'description' => 'Quần jeans nam xanh đậm, form slim fit tôn dáng, chất liệu denim co giãn thoải mái.',
        'short_description' => 'Jeans nam xanh đậm slim fit co giãn',
        'price' => 650000,
        'weight' => 0.7,
        'category_ids' => [15], // Quần Jeans Nam
        'attribute_set_id' => 4
    ],
    [
        'sku' => 'GIAY-SNEAKER-NU-001',
        'name' => 'Giày Sneaker Nữ Hồng Pastel',
        'description' => 'Giày sneaker nữ màu hồng pastel, thiết kế cute và năng động. Đế cao su chống trượt.',
        'short_description' => 'Sneaker nữ hồng pastel cute và năng động',
        'price' => 1350000,
        'weight' => 0.7,
        'category_ids' => [9], // Giày Sneaker Nữ
        'attribute_set_id' => 4
    ]
];

function createProduct($productData) {
    global $productFactory, $productRepository, $categoryLinkManagement;
    
    try {
        $product = $productFactory->create();
        $product->setSku($productData['sku'])
                ->setName($productData['name'])
                ->setAttributeSetId($productData['attribute_set_id'])
                ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
                ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
                ->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
                ->setPrice($productData['price'])
                ->setWebsiteIds([1])
                ->setStockData([
                    'use_config_manage_stock' => 1,
                    'manage_stock' => 1,
                    'is_in_stock' => 1,
                    'qty' => 100
                ])
                ->setWeight($productData['weight'])
                ->setDescription($productData['description'])
                ->setShortDescription($productData['short_description']);

        $product = $productRepository->save($product);
        
        // Gán category
        if (!empty($productData['category_ids'])) {
            $categoryLinkManagement->assignProductToCategories($productData['sku'], $productData['category_ids']);
        }
        
        echo "Đã tạo sản phẩm: " . $productData['name'] . " (SKU: " . $productData['sku'] . ")\n";
        
    } catch (Exception $e) {
        echo "Lỗi khi tạo sản phẩm " . $productData['name'] . ": " . $e->getMessage() . "\n";
    }
}

// Tạo tất cả sản phẩm
foreach ($products as $productData) {
    createProduct($productData);
}

echo "Hoàn thành tạo sản phẩm mẫu!\n";
?>