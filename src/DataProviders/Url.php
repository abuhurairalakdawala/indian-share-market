<?php
namespace IndianShareMarket\DataProviders;

class Url
{
    public static $nseStocks = 'https://www.nseindia.com/content/equities/EQUITY_L.csv';

    public static $bseStocks = 'https://www.bseindia.com/corporates/List_Scrips.aspx';

    public static $nseSectors = 'https://www.nseindia.com/content/indices/ind_close_all_ddmmyyyy.csv';

    public static $bseSectors = 'https://api.bseindia.com/BseIndiaAPI/api/Dropdowndata/w';

    public static $bseIndustries = 'https://www.bseindia.com/corporates/List_Scrips.aspx';

    public static $nseIndustries = 'http://www.moneycontrol.com/stocks/sectors/';

    public static $getNseQuote = 'https://www.nseindia.com/live_market/dynaContent/live_watch/get_quote/GetQuote.jsp?symbol=';

    public static $getBseQuote = 'https://api.bseindia.com/BseIndiaAPI/api/StockReachGraph/w?flag=0&fromdate=&todate=&seriesid=&scripcode=';
}
