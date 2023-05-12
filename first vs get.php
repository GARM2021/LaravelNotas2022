20230511
When you're using get() you get a collection. In this case you need to iterate over it to get properties:

@foreach ($collection as $object)
    {{ $object->title }}
@endforeach
Or you could just get one of objects by it's index:

{{ $collection[0]->title }}
Or get first object from collection:

{{ $collection->first() }}
When you're using find() or first() you get an object, so you can get properties with simple:

{{ $object->title }}