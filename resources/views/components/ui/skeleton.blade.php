@props(['rows' => 5, 'cols' => 4])

<div class="table-container" aria-busy="true" aria-label="Cargando datos…">
    <table class="table">
        <tbody class="divide-y divide-neutral-100">
            @for ($r = 0; $r < $rows; $r++)
                <tr>
                    @for ($c = 0; $c < $cols; $c++)
                        <td class="td">
                            <div class="skeleton h-4 {{ $c === 0 ? 'w-24' : ($c === $cols - 1 ? 'w-16' : 'w-32') }}"></div>
                        </td>
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>
</div>
