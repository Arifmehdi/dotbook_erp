<div id="jstree_demo_div">
    @php
        function printChild($subCategories) {
            
            foreach($subCategories as $subCategory) {

                echo '<ul class="ps-3">';
                echo '<li class="fw-bold jstree-open">';
                if (auth()->user()->can('cost_centre_categories_edit')) {

                    echo '<span href="'. route('cost.centres.categories.edit', $subCategory->id) .'" data-class_name="' . $subCategory->id . '" class="' . $subCategory->id . '" data-btn_type="edit_category" id="edit">'. $subCategory->name .'</span> ';
                }else {
                    
                    echo '<span data-class_name="' . $subCategory->id . '" class="' . $subCategory->id . '">'. $subCategory->name .'</span> ';
                }

                if (auth()->user()->can('cost_centre_categories_add')) {
                    echo '<span href="'.route('cost.centres.categories.create').'" data-category_id="'. $subCategory->id .'" data-btn_type="add_category" class="fa-sharp fa-solid fa-plus ms-1 text-success fw-icon add_btn_frm_group" id="addBtn"></span> ';
                }

                if (auth()->user()->can('cost_centre_categories_delete')) {

                    echo '<span href="'. route('cost.centres.categories.delete', $subCategory->id) .'" class="far fa-trash-alt text-primary ms-1 fw-icon delete_btn" id="delete"></span>';
                }

                if (count($subCategory->costCentres) > 0) {

                    $mt = 'data-jstree=\'{"icon":"fa-light fa-memo-circle-check account_icon"}\'';

                    foreach ($subCategory->costCentres as $costCentre) {

                        echo '<ul class="ps-3">';
                        echo '<li class="fw-bold jstree-open" '. $mt .'>';
                        if (auth()->user()->can('cost_centres_edit')) {

                            echo '<span href="'. route('cost.centres.edit', $costCentre->id) .'" data-class_name="' . $subCategory->id.$costCentre->id . '" class="' . $subCategory->id.$costCentre->id . '" data-btn_type="edit_cost_centre" id="edit" '. $mt .'>'. $costCentre->name .'</span> ';
                        }else {
                            
                            echo '<span data-class_name="' . $subCategory->id.$costCentre->id . '" class="' . $subCategory->id.$costCentre->id . '" '. $mt .'>'. $costCentre->name .'</span> ';
                        }

                        if (auth()->user()->can('cost_centres_delete')) {

                            echo '<span href="'. route('cost.centres.delete', $costCentre->id) .'" class="far fa-trash-alt text-primary ms-1 fw-icon delete_btn" id="delete"></span>';
                        }
                        echo '</li>';
                        echo '</ul>';
                    } 
                }
                
                if(count($subCategory->subCategories) > 0) {

                    printChild($subCategory->subCategories);
                }

                echo '</li>';
                echo '</ul>';
            }
        }
    @endphp

    @foreach ($categories as $category)
        <ul>
            <li class="fw-bold parent jstree-open">
                @if (auth()->user()->can('cost_centre_categories_edit')) 

                    <span href="{{ route('cost.centres.categories.edit', $category->id) }}" data-class_name="{{ $category->id }}" data-btn_type="edit_category" id="edit" class="{{ $category->id }}">{{ $category->name }}</span>
                @else   

                    <span data-class_name="{{ $category->id }}" class="{{ $category->id }}">{{ $category->name }}</span>
                @endif

                @if (auth()->user()->can('cost_centre_categories_add')) 

                    <span href="{{ route('cost.centres.categories.create') }}" data-category_id="{{ $category->id }}" data-btn_type="add_category" class="fa-sharp fa-solid fa-plus ms-1 text-success fw-icon add_btn_frm_group" id="addBtn"></span>
                @endif

                @if (auth()->user()->can('cost_centre_categories_delete'))

                    <span href="{{ route('cost.centres.categories.delete', $category->id) }}" class="far fa-trash-alt text-primary ms-1 fw-icon delete_btn" id="delete"></span>
                @endif

                @php
                    if (count($category->costCentres) > 0) {

                        $mt = 'data-jstree=\'{"icon":"fa-light fa-memo-circle-check account_icon"}\'';

                        foreach ($category->costCentres as $costCentre) {

                            echo '<ul class="ps-3">';
                            echo '<li class="fw-bold jstree-open" '. $mt .'>';

                            if (auth()->user()->can('cost_centres_edit')){

                                echo '<span href="'. route('cost.centres.edit', $costCentre->id) .'" data-class_name="' . $category->id.$costCentre->id . '" class="' . $category->id.$costCentre->id . '" data-btn_type="edit_cost_centre" id="edit" '. $mt .'>'. $costCentre->name .'</span> ';
                            }else {
                                
                                echo '<span data-class_name="' . $category->id.$costCentre->id . '" class="' . $category->id.$costCentre->id . '" '. $mt .'>'. $costCentre->name .'</span> ';
                            }

                            if (auth()->user()->can('cost_centres_delete')){
                                
                                echo '<span href="'. route('cost.centres.delete', $costCentre->id) .'" class="far fa-trash-alt text-primary ms-1 fw-icon delete_btn" id="delete"></span>';
                            }

                            echo '</li>';
                            echo '</ul>';
                        } 
                    }
                @endphp

                @php
                    if (count($category->subCategories) > 0) {

                        printChild($category->subCategories);
                    }
                @endphp
            </li>
        </ul> 
    @endforeach
</div>

<script>
    $('#jstree_demo_div').jstree(
        {
            "core" : {
                "multiple" : true,
                "animation" : 0
            }
        }
    );
</script>