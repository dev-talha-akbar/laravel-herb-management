<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
@role('Super Admin')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('admin') }}'><i class='nav-icon fa fa-user'></i> Admins</a></li>
@endrole
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('herb') }}'><i class='nav-icon fa fa-leaf'></i> Herbs</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('herb-formula') }}'><i class='nav-icon fa fa-flask'></i> Herb Formulas</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('adminsignssymptoms') }}'><i class='nav-icon fa fa-hand-pointer-o'></i> Signs & Symptoms</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('adminchemicalcompositions') }}'><i class='nav-icon fa fa-flask'></i> Chemical Compositions</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('adminproperties') }}'><i class='nav-icon fa fa-flask'></i> Properties</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('adminsystemsaffected') }}'><i class='nav-icon fa fa-flask'></i> Systems affected</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('adminactions') }}'><i class='nav-icon fa fa-flask'></i> Actions</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('adminpharmacology') }}'><i class='nav-icon fa fa-flask'></i> Pharmacology</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('adminantibioticstrains') }}'><i class='nav-icon fa fa-flask'></i> Antibiotic Strains</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('adminhormones') }}'><i class='nav-icon fa fa-flask'></i> Hormones</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('adminherbherbinteraction') }}'><i class='nav-icon fa fa-leaf'></i> Herb-Herb interaction</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('adminherbdruginteraction') }}'><i class='nav-icon fa fa-leaf'></i> Herb-Drug interaction</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('admintoxicitycontraindications') }}'><i class='nav-icon fa fa-flask'></i> Toxicity / Contraindications</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('submission') }}'><i class='nav-icon fa fa-file-text'></i> Submissions</a></li>