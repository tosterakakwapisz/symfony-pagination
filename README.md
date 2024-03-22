# Symfony pagination

Very simple pagination for PHP/Symfony

## Usage

```php
$pagesCount = (int) ceil($itemsCount / $itemsPerPage);
$offset = ($page - 1) * $itemsPerPage;
$entities = $entityRepository->findBy(['some'=>'criteria'], ['id' => 'DESC'], self::ITEMS_PER_PAGE, $offset);

$pagination = PaginationService::getPaginationData(
    $page,
    $pagesCount,
    $request->getRequestUri()
);

return $this->render('entities_template.twig', [
    'entities' => $entities,
    'pagination' => $pagination,
]);
```
