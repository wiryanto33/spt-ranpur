<tr>
    <td>
        <select name="items[IDX][sparepart_id]" class="form-control" required>
            <option value="">Pilih sparepart</option>
            @foreach ($spareparts as $sp)
                <option value="{{ $sp->id }}">{{ $sp->nama }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="number" min="1" name="items[IDX][qty_diminta]" class="form-control" placeholder="Qty" required>
    </td>
    <td class="text-center">
        <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fas fa-times"></i></button>
    </td>
</tr>

