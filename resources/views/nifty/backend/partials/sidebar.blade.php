                <div class="media user-media">
                    <a class="user-link" href="{{ URL::to('dashboard/users/profile') }}">
                        <span class="fa fa-user fa-4x img-thumbnail user-img text-danger"></span> 
                    </a> 
                    <div class="media-body">
                        <h5 class="media-heading">{{ $user->first_name . ' ' . $user->last_name }}</h5>
                        <ul class="list-unstyled user-info">
                            <li><span class="label label-danger">{{ $user->getGroups()[0]->name; }}</span></li>
                            <li>You logged in :
                                <br>
                                <small><i class="fa fa-calendar"></i>&nbsp; {{ $logged_in_for }}</small> 
                            </li>
                        </ul>
                    </div>
                </div>

                <ul id="menu" class="collapse">
                    <li class="nav-header">Menu</li>
                    <li class="nav-divider"></li>
                    <li class="{{ $active=='index' ? 'active' : '' }}">
                        <a href="{{ URL::to('dashboard') }}"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a>
                    </li>
                    <li class="{{ $activeParent=='pages' ? 'active' : '' }}">
                        <a href=""><i class="fa fa-tasks"></i>&nbsp;Pages <span class="fa arrow"></span> </a> 
                        <ul>
                            <li class="{{ $active=='allpages' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/pages') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;All Pages 
                                </a>
                            </li>
                            <li class="{{ $active=='createpage' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/pages/create') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;New Page
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ $activeParent=='blog' ? 'active' : '' }}">
                        <a href="javascript:;"><i class="fa fa-quote-left"></i>&nbsp;Blog <span class="fa arrow"></span> </a> 
                        <ul>
                            <li class="{{ $active=='allposts' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/blog/posts') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;All Posts
                                </a>
                            </li>
                            <li class="{{ $active=='createpost' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/blog/posts/create') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;New Post
                                </a>
                            </li>
                            <li class="{{ $active=='allcategories' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/blog/categories') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;Categories
                                </a>
                            </li>
                            <li class="{{ $active=='createcategory' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/blog/categories/create') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;New Category
                                </a>
                            </li>
                        </ul>
                    </li>                  
                    <li class="{{ $activeParent=='users' ? 'active' : '' }}">
                        <a href="javascript:;"><i class="fa fa-group"></i>&nbsp;Users <span class="fa arrow"></span> </a> 
                        <ul>
                            @if ( $isAdmin )
                                <li class="{{ $active=='profile' ? 'active' : '' }}">
                                    <a href="{{ URL::to('dashboard/users/profile') }}">
                                        <i class="fa fa-angle-right"></i>&nbsp;Your Profile
                                    </a>
                                </li>
                                <li class="{{ $active=='allusers' ? 'active' : '' }}">
                                    <a href="{{ URL::to('dashboard/users/') }}">
                                        <i class="fa fa-angle-right"></i>&nbsp;All Users 
                                    </a>
                                </li>
                                <li class="{{ $active=='createuser' ? 'active' : '' }}">
                                    <a href="{{ URL::to('dashboard/users/create') }}">
                                        <i class="fa fa-angle-right"></i>&nbsp;New User
                                    </a>
                                </li>
                            @else
                                <li class="{{ $active=='profile' ? 'active' : '' }}">
                                    <a href="{{ URL::to('dashboard/users/profile') }}">
                                        <i class="fa fa-angle-right"></i>&nbsp;Your Profile
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                </ul><!-- /#menu -->