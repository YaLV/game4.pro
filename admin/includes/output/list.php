<?
$contents="
<div class='shadow box filters'>
  <div class='btn-toolbar' style='margin: 0px;margin-top:2px;'>
    <div class='btn-group'>
      <button class='btn btn-inverse filter-btn dropdown-toggle' id='filterage' data-toggle='dropdown'>No Age Filter</button>
      <button class='btn btn-inverse dropdown-toggle' data-toggle='dropdown'>
        <span class='caret'></span>
      </button>
      <ul class='dropdown-menu'>
        <li><a href='javascript:void(0);' id='ageno'>No Age Filter</a></li>
        <li><a href='javascript:void(0);' id='age3'>Age 3+</a></li>
        <li><a href='javascript:void(0);' id='age7'>Age 7+</a></li>
        <li><a href='javascript:void(0);' id='age12'>Age 12+</a></li>
        <li><a href='javascript:void(0);' id='age15'>Age 15+</a></li>
        <li><a href='javascript:void(0);' id='age16'>Age 16+</a></li>
        <li><a href='javascript:void(0);' id='age18'>Age 18+</a></li>
      </ul>
    </div>
    <div class='btn-group'>
      <button class='btn btn-inverse filter-btn dropdown-toggle' id='filterprice' data-toggle='dropdown'>No Price Filter</button>
      <button class='btn btn-inverse dropdown-toggle' data-toggle='dropdown'>
        <span class='caret'></span>
      </button>
      <ul class='dropdown-menu'>
        <li><a href='javascript:void(0);' id='priceno'>No Price Filter</a></li>
        <li><a href='javascript:void(0);' id='price001-999'>0.01 Eur - 9.99 Eur</a></li>
        <li><a href='javascript:void(0);' id='price1000-1999'>10.00 Eur - 19.99 Eur</a></li>
        <li><a href='javascript:void(0);' id='price2000-2999'>20.00 Eur - 29.99 Eur</a></li>
        <li><a href='javascript:void(0);' id='price3000-4999'>30.00 Eur - 49.99 Eur</a></li>
      </ul>
    </div>
    <div class='btn-group'>
      <button class='btn btn-inverse filter-btn dropdown-toggle' id='filtergenre' data-toggle='dropdown'>No Genre Filter</button>
      <button class='btn btn-inverse dropdown-toggle' data-toggle='dropdown'>
        <span class='caret'></span>
      </button>
      <ul class='dropdown-menu'>
        <li><a href='javascript:void(0);' id='typeno'>No Genre Filter</a></li>
        <li><a href='javascript:void(0);' id='typeAction'>Action</a></li>
        <li><a href='javascript:void(0);' id='typeAdventure'>Adventure</a></li>
        <li><a href='javascript:void(0);' id='typeStrategy'>Strategy</a></li>
        <li><a href='javascript:void(0);' id='typeRacing'>Racing</a></li>
        <li><a href='javascript:void(0);' id='typeRPG'>RPG</a></li>
        <li><a href='javascript:void(0);' id='typeShooter'>Shooter</a></li>
        <li><a href='javascript:void(0);' id='typeSimulation'>Simulation</a></li>
        <li><a href='javascript:void(0);' id='typeSport'>Sport</a></li>
        <li><a href='javascript:void(0);' id='typeMMORPG'>MMORPG</a></li>
      </ul>
    </div>
    <button class='btn btn-danger reset pull-right' style='margin-right:10px;'>Reset</button>
    <form class='navbar-search pull-right' style='float:right;margin:0px;margin-right:10px;' action=''>
          <input type='text' class='search-query span2' id='searchField' placeholder='Search'>
    </form>
  </div>
 </div>
 
<div class='shadow box content'>
  <div style='float:center;color:white;' class='pageDiv'></div>
  <table class='content-table'>
  </table>
  <div style='float:center;color:white;' class='pageDiv'></div>
</div>
";