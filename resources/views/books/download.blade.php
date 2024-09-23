<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Author</th>
        <th>Publisher</th>
    </tr>
    </thead>
    <tbody>
    @foreach($books as $book)
        <tr>
            <td bgcolor="aqua">{{ $book->title }}</td>
            <td>{{ $book->author }}</td>
            <td>{{ $book->publisher }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
