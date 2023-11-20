<div id="jstree_demo_div">
    @php

        function printChild($items) {

            foreach($items as $item) {
                echo '<ul class="ps-3">';
                echo '<li class="fw-bold jstree-open">';

                if (auth()->user()->can('account_groups_edit')) {

                    echo '<span href="'. route('accounting.groups.edit', $item->id) .'" data-class_name="' . $item->id.'group' . '" class="' . $item->id.'group' . '" id="editAccountGroupBtn">'. $item->name .'</span> ';
                }else {

                    echo '<span data-class_name="' . $item->id.'group' . '" class="' . $item->id.'group' . '">'. $item->name .'</span> ';
                }

                if (auth()->user()->can('account_groups_add')) {

                    echo '<span href="'. route('accounting.groups.create') .'" data-group_id="'. $item->id .'" class="fa-sharp fa-solid fa-plus ms-1 text-success fw-icon add_btn_frm_group" id="addAccountGroupBtn"></span>';
                }

                if (auth()->user()->can('account_groups_delete')) {

                    echo '<span href="'. route('accounting.groups.delete', $item->id) .'" class="far fa-trash-alt text-primary ms-1 fw-icon delete_btn" id="delete"></span>';
                }

                if (count($item->accounts) > 0) {

                    $mt = 'data-jstree=\'{"icon":"fa-light fa-memo-circle-check account_icon"}\'';

                    foreach ($item->accounts as $account) {

                        echo '<ul class="ps-3">';
                        echo '<li class="fw-bold jstree-open" '. $mt .'>';

                        if (auth()->user()->can('accounts_edit')) {

                            echo '<span href="'. route('accounting.accounts.edit', $account->id) .'" data-class_name="' .$account->id.'account' . '" class="' . $account->id.'account' . '" id="editAccount" '. $mt .'>'. $account->name. ($account->phone ? '/'.$account->phone : '') . ($account->account_number ? '/'.$account->account_number : '') .'</span> ';
                        }else {

                            echo '<span data-class_name="' . $account->id.'account' . '" class="' . $account->id.'account' . '" '. $mt .'>'. $account->name($account->phone ? '/'.$account->phone : '') . ($account->account_number ? '/'.$account->account_number : '') .'</span> ';
                        }

                        if (auth()->user()->can('accounts_ledger')) {
                            echo '<span href="' . route('accounting.accounts.ledger', [$account->id, 'accountId']) . '" target="__black" title="Ledger" class="fa-regular fa-bars text-primary ms-1 fw-icon" id="viewLedger"></span>';
                        }
                        if (auth()->user()->can('accounts_delete')) {

                            echo '<span href="' . route('accounting.accounts.delete', $account->id) . '" class="far fa-trash-alt text-primary ms-1 fw-icon delete_btn" id="delete"></span>';
                        }
                        echo '</li>';
                        echo '</ul>';
                    }
                }

                if(count($item->subgroupsAccounts) > 0) {

                    printChild($item->subgroupsAccounts);
                }

                echo '</li>';
                echo '</ul>';
            }
        }
    @endphp

    @foreach ($groups as $group)
        <ul>
            <li class="fw-bold parent jstree-open">
                <span data-class_name="{{ $group->id }}" id="parentText" class="{{ $group->id }}">
                    {{ $group->name }}
                </span>
                @php
                    if (count($group->subgroupsAccounts) > 0) {

                        printChild($group->subgroupsAccounts);
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
