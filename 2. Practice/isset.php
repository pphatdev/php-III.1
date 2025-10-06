<?php

$searchQuery = $_GET['q'];
$query = isset($searchQuery) ? $searchQuery : 'No search query provided';

echo "Search Query: {$query}";