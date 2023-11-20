<style>
    #shortcut-section {
        position: fixed;
        bottom: 0;
        right: 0;
        left: 40px;
        height: 35px;
        background: #fff;
        border-top: 1px solid rgba(0, 0, 0, .2);
        overflow: auto;
        -ms-overflow-style: none;
        scrollbar-width: none;
        z-index: 98;
    }
    #shortcut-section::-webkit-scrollbar {
        display: none;
    }
    #shortcut-section.menu-expanded {
        left: 220px;
    }
    #shortcut-section.has-horizontal {
        left: 0;
    }
    #shortcut-section .shortcut-list {
        min-width: max-content;
        display: flex;
        padding: 5px 10px;
        gap: 10px;
    }
    #shortcut-section .shortcut-list li {
        border: 1px solid rgba(0, 0, 0, .1);
        border-radius: 3px;
        background: rgba(0, 0, 0, .05);
        padding: 0 10px;
        height: 25px;
        line-height: 23px;
        font-size: 12px;
    }
    #shortcut-section .shortcut-list span {
        color: #4383be;
        font-weight: 600;
        text-decoration: underline;
    }
</style>
<div class="has-vertical" id="shortcut-section">
    <ul class="shortcut-list">
        <li><span>Ctrl + Enter :</span> Save & Print</li>
        <li><span>Shift + Enter :</span> Save</li>
        <li><span>Alt + C :</span> Add Customer</li>
        <li><span>Alt + P :</span> Add Item</li>
        <li><span>Q :</span> Quit</li>
        <li><span>A :</span> Accept</li>
        <li><span>D :</span> Delete</li>
        <li><span>X :</span> Cancel Vch</li>
        <li><span>Q :</span> Quit</li>
        <li><span>A :</span> Accept</li>
        <li><span>D :</span> Delete</li>
        <li><span>X :</span> Cancel Vch</li>
        <li><span>Q :</span> Quit</li>
        <li><span>A :</span> Accept</li>
        <li><span>D :</span> Delete</li>
        <li><span>X :</span> Cancel Vch</li>
    </ul>
</div>
