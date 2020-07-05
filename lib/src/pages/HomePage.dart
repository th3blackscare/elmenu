import 'package:bubble_animated_tabbar/bubble_animated_tabbar.dart';
import 'package:elmenu/src/BLoC.dart';
import 'package:elmenu/src/pages/SearchPageView.dart';
import 'dart:io' show Platform;
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';

import 'MenuPageView.dart';
import 'WebViewe.dart';

class HomePageView extends StatefulWidget {
  BLoC bloc;

  HomePageView({Key key, this.bloc});

  @override
  _HomePageViewState createState() => _HomePageViewState();
}

class _HomePageViewState extends State<HomePageView> with SingleTickerProviderStateMixin {
  int _currentIndex = 0;
  TabController tabController;
  List<Map> children = [
    {
      'icon': FontAwesomeIcons.elementor,
      'title': 'Menu',
      'iconSize':23,
      'color': Colors.red.shade300,
      'textColor': Colors.white,
      'customTextStyle':TextStyle(fontSize: 17, color: Colors.white),
      'tabPadding': EdgeInsets.fromLTRB(10, 6, 10, 6)
    },
    {
      'icon': FontAwesomeIcons.newspaper,
      'title': 'News',
      'iconSize':23,
      'color': Color.fromRGBO(18, 140, 126, 0.25),
      'textColor': Color.fromRGBO(6, 125, 111, 1),
      'customTextStyle':TextStyle(fontSize: 17, color: Color.fromRGBO(6, 125, 111, 1)),
      'tabPadding': EdgeInsets.fromLTRB(10, 6, 10, 6)
    },
  ];

  @override
  void initState() {
    super.initState();
    tabController = TabController(vsync: this, initialIndex: 0, length: children.length);
    tabController.addListener(onTabViewChange);
  }
  getBoxDecoration() {
    return BoxDecoration(
        color: Colors.grey.shade50,
        border: Border(top: BorderSide(width: 1,color: Colors.grey.withOpacity(0.5)),bottom:BorderSide(width: 1,color: Colors.grey.withOpacity(0.5))),
        boxShadow: [new BoxShadow(color: Colors.grey.withOpacity(0.5), blurRadius: 1.0, spreadRadius: 1.0,offset: Offset(0,2))]);
  }

  void onTabTapped(int index) {
    setState(() {
      _currentIndex = index;
      tabController.animateTo(index);
    });
  }

  onTabViewChange() {
    setState(() {
      _currentIndex = tabController.index;
    });
  }

  getPage() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: <Widget>[
          Text('You are on Page'),
          Text('${_currentIndex + 1}',style: Theme.of(context).textTheme.display1),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      floatingActionButton: (Platform.isWindows)
          ?null
          :FloatingActionButton(
            onPressed: () {
              Navigator.push(context, MaterialPageRoute(builder: (context) => WebViewPage("Online Chat")));
            },
          child: FaIcon(FontAwesomeIcons.commentDots),
        //splashColor: Colors.red.shade300,
          ),
      body: Container(
          child: Column(
            children: [
            Container(
              width: MediaQuery.of(context).size.width,
              height: 60,
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(" Crinkle",style: TextStyle(fontFamily: 'Baloo2Bold',fontWeight: FontWeight.bold,fontSize: 30,color: Theme.of(context).primaryColor),),
                  IconButton(icon: FaIcon(FontAwesomeIcons.search), onPressed: (){showSearch(context: context, delegate: SearchPageView(menuItems: widget.bloc.Menu));})
                ],
              ),
            ),
            AnimatedTabbar(
              padding:EdgeInsets.only(left: 4, top: 6, right: 4, bottom: 6),
              containerDecoration: getBoxDecoration(),
              currentIndex: _currentIndex,
              onTap: onTabTapped,
              children: children,
            ),
            Expanded(
              child: Container(
                width: MediaQuery.of(context).size.width,
                //height: 200,
                child: TabBarView(
                  children: [ MenuPageView(bLoC: widget.bloc,),getPage(),],
                  controller: tabController,
                ),
              ),
            ),
          ],)
      ),
    );
  }
}
