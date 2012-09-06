<?

class Listing {
	static function search($category, $area, $args, $appendQuery = null) {
		$query = new AndQuery(
			new Query('category', $category->id)
			,new Query('areas', $area->id)
			,new Query('status', 0)
		);

		if ($appendQuery)
			$query->add($appendQuery);

		$allowedOptions = array(
			'size' => true,
			'from' => true,
		);
		$opts = array_intersect_key($args, $allowedOptions);
		$args = array_diff_key($args, $allowedOptions);
		foreach ($args as $field => $value) {
			if (preg_match('/^\[(\d*),(\d*)\]$/', $value, $m)) {
				$queryClass = substr($field,-4) === 'Time' ? 'DateRangeQuery' : 'RangeQuery';
				$query->add(new $queryClass($queryClass == 'RangeQuery' ? $field . '_i' : $field, $m[1] ?: null, $m[2] ?: null));
			} else {
				$useMuti = false;
				if (preg_match("/^m[0-9]+$/", $value, $match)) {
					try {
						$node = graph($value);
						if ($node->type() == 'Entity')
							$useMuti = true;
					} catch(Exception $e) {}
				}
				if ($useMuti)
					$query->add(new OrQuery(
						new Query($field, $value)
						,new Query($field . '_s', $value)
					));
				else
					$query->add(new Query($field, $value));
			}
		}
		return Searcher::query('Ad', $query, $opts);
	}

}