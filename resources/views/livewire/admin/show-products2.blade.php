<div>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-semibold text-xl text-gray-600 leading-right">
                Detalles de un completos de un producto
            </h2>
            <x-button-link class="ml-auto" href="{{ route('admin.products.create') }}">
                Agregar producto
            </x-button-link>
        </div>
    </x-slot>

    <x-table-responsive>
        <div class="mt-5 ml-6">
            <div class="mb-4">
                <div @click.away="dropdownPagination = false" x-data="{dropdownPagination: false}"
                    class="relative inline-block mb-0">
                    <x-button-link @click="dropdownPagination = !dropdownPagination">
                        <i class="fa-solid fa-book-open"></i>
                        <span class="ml-1">Paginación</span>
                    </x-button-link>
                    <select x-show="dropdownPagination"
                        class="w-7/12 absolute left-0 mt-12 bg-gray-100 rounded-md shadow-xl" wire:model="pagination">
                        <option value="" selected disabled>Productos a mostrar</option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>

                <div @click.away="dropdownColumns = false" x-data="{dropdownColumns: false}"
                    class="relative inline-block">
                    <x-button-link @click="dropdownColumns = !dropdownColumns">
                        <i class="fa-solid fa-table-columns"></i>
                        <span class="ml-1">Columnas</span>
                    </x-button-link>
                    <div x-show="dropdownColumns" class="absolute left-0 w-40 mt-2 bg-gray-100 rounded-md shadow-xl">
                        <span href="#" class="block px-4 py-2 text-sm">
                            @foreach ($columns as $column)
                                <input type="checkbox" wire:model="selectedColumns" value="{{ $column }}">
                                <label>{{ $column }}</label>
                                <br />
                            @endforeach
                        </span>
                    </div>
                </div>

                <div x-data="{dropdownMenu: false}" class="absolute inline-block ml-1">
                    <x-button-link @click="dropdownMenu = ! dropdownMenu"
                        class="ml-5 flex items-center p-2 bg-white rounded-md">
                        <i class="fa-solid fa-filter"></i>
                        <span class="ml-1">Mostrar Filtros </span>
                    </x-button-link>
                    <div x-show="dropdownMenu" class="absolute left-1 py-2 mt-2 bg-white rounded-md shadow-xl">
                        <aside>
                            <x-jet-input wire:model="categorySearch" type="text" placeholder="Busca por categoría" />

                            <x-jet-input wire:model="subcategorySearch" type="text"
                                placeholder="Busca por subcategoría" />

                            <x-jet-input wire:model="brandSearch" type="text" placeholder="Busca por marca" />

                            <select wire:model="status" class="form-control w1/3">
                                <option value="" selected disabled>Seleccionar el estado</option>
                                <option value="2">Publicado</option>
                                <option value="1">No Publicado</option>
                            </select>

                            <x-jet-input wire:model="priceSearch" type="text" placeholder="Busca por precio" />

                            <input type="checkbox" wire:model="colorsSearch">
                            <label>Color</label>

                            <input type="checkbox" wire:model="sizesSearch">
                            <label>Talla</label>

                            <x-jet-button class="mt-4 p-2" wire:click="clearFilters">
                                <i class="fa-solid fa-eraser mr-1"></i>
                                Eliminar Filtros
                            </x-jet-button>
                        </aside>
                    </div>
                </div>
                <br>
            </div>

            <x-jet-input class="w-1/2" wire:model="search" type="text"
                placeholder="Introduzca el nombre del producto a buscar" />
            <x-button-link wire:click="clear" class="h-10">
                <span class="fa fa-eraser"></span>
            </x-button-link>
        </div>
        <div class="px-6 py-4 mt-0">
            @if ($products->count())
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            {{-- <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vendidos
                            </th> --}}
                            @if ($this->showColumn('Nombre'))
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre
                                    <button wire:click="sortable('name')">
                                        <span class="fa fa{{ $camp === 'name' ? $icon : '-circle' }}"></span>
                                    </button>
                                </th>
                            @endif
                            @if ($this->showColumn('Categoría'))
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Categoría
                                    <button wire:click="sortable('')">
                                        <span class="fa fa{{ $camp === '' ? $icon : '-circle' }}"></span>
                                    </button>
                                </th>
                            @endif
                            @if ($this->showColumn('Estado'))
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                    <button wire:click="sortable('status')">
                                        <span class="fa fa{{ $camp === 'status' ? $icon : '-circle' }}"></span>
                                    </button>
                                </th>
                            @endif
                            @if ($this->showColumn('Precio'))
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Precio
                                    <button wire:click="sortable('price')">
                                        <span class="fa fa{{ $camp === 'price' ? $icon : '-circle' }}"></span>
                                    </button>
                                </th>
                            @endif
                            @if ($this->showColumn('Subcategoría'))
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subcategorías
                                    <button wire:click="sortable('subcategory_id')">
                                        <span
                                            class="fa fa{{ $camp === 'subcategory_id' ? $icon : '-circle' }}"></span>
                                    </button>
                                </th>
                            @endif
                            @if ($this->showColumn('Marca'))
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Marcas
                                    <button wire:click="sortable('brand_id')">
                                        <span class="fa fa{{ $camp === 'brand_id' ? $icon : '-circle' }}"></span>
                                    </button>
                                </th>
                            @endif
                            @if ($this->showColumn('Stock'))
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Stock
                                    <button wire:click="sortable('quantity')">
                                        <span class="fa fa{{ $camp === 'quantity' ? $icon : '-circle' }}"></span>
                                    </button>
                                </th>
                            @endif
                            @if ($this->showColumn('Fecha Creación'))
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha de Creación
                                    <button wire:click="sortable('created_at')">
                                        <span class="fa fa{{ $camp === 'created_at' ? $icon : '-circle' }}"></span>
                                    </button>
                                </th>
                            @endif
                            @if ($this->showColumn('Fecha Edición'))
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha de Actualización
                                    <button wire:click="sortable('updated_at')">
                                        <span class="fa fa{{ $camp === 'updated_at' ? $icon : '-circle' }}"></span>
                                    </button>
                                </th>
                            @endif
                            @if ($this->showColumn('Colores'))
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Colores
                                </th>
                            @endif
                            @if ($this->showColumn('Tallas'))
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tallas
                                </th>
                            @endif
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Editar</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($products as $product)
                            {{-- <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                    </div>
                                </td>
                            </tr> --}}
                            <tr>
                                @if ($this->showColumn('Nombre'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 object-cover">
                                                <img class="h-10 w-10 rounded-full"
                                                    src="{{ $product->images->count() ? Storage::url($product->images->first()->url) : 'img/default.jpg' }}"
                                                    alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $product->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                                @if ($this->showColumn('Categoría'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $product->subcategory->category->name }}
                                        </div>
                                    </td>
                                @endif
                                @if ($this->showColumn('Estado'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-$product->status == 1 ? 'red' : 'green' }}-100 text-{{ $product->status == 1 ? 'red' : 'green' }}-800">
                                            {{ $product->status == 1 ? 'Borrador' : 'Publicado' }}
                                        </span>
                                    </td>
                                @endif
                                @if ($this->showColumn('Precio'))
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $product->price }} &euro;
                                    </td>
                                @endif
                                @if ($this->showColumn('Subcategoría'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $product->subcategory->name }}
                                        </div>
                                    </td>
                                @endif
                                @if ($this->showColumn('Marca'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $product->brand->name }}
                                        </div>
                                    </td>
                                @endif
                                @if ($this->showColumn('Stock'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if (is_null($product->quantity))
                                            @if ($product->colors->count())
                                                <div class="text-sm text-gray-900">
                                                    {{ array_sum($product->colors->pluck('pivot')->pluck('quantity')->all()) }}
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-900">
                                                    {{ array_sum($product->sizes->pluck('colors')->collapse()->pluck('pivot')->pluck('quantity')->all()) }}
                                                </div>
                                            @endif
                                        @else
                                            <div class="text-sm text-gray-900">
                                                {{ $product->quantity }}
                                            </div>
                                        @endif
                                    </td>
                                @endif
                                @if ($this->showColumn('Fecha Creación'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $product->created_at }}
                                        </div>
                                    </td>
                                @endif
                                @if ($this->showColumn('Fecha Edición'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $product->updated_at }}
                                        </div>
                                    </td>
                                @endif
                                @if ($this->showColumn('Colores'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($product->sizes)
                                            <div class="text-sm text-gray-900">
                                                {{ implode(', ',array_unique($product->sizes->pluck('colors')->collapse()->pluck('name')->all())) }}
                                            </div>
                                        @endif
                                        @if ($product->colors)
                                            <div class="text-sm text-gray-900">
                                                {{ implode(', ', $product->colors->pluck('name')->all()) }}
                                            </div>
                                        @endif
                                    </td>
                                @endif
                                @if ($this->showColumn('Tallas'))
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p>{{ implode($product->sizes->pluck('name')->all(), ', ') }}</p>
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                        class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-4">
                    No existen productos coincidentes
                </div>
            @endif
            @if ($products->hasPages())
                <div class="px-6 py-4">
                    {{ $products->links() }}
                </div>
            @endif
    </x-table-responsive>
</div>
