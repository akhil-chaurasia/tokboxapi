/*
 * Copyright (c) 2008 Syedur Islam
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *		
 *************************************

 *
 *  TokBox API Configuration Loader  *
 *
 *************************************
 *
 * Original Java Code by Melih Onvural, August 2008

 * Converted to C# by Syedur Islam, November 2008

 *

 *

 */
using System;
using System.Configuration;

namespace TokBox
{
    public class API_Config
    {
        /// <summary>
        /// Partner Key provided by TokBox
        /// </summary>
        public static string PARTNER_KEY = ConfigurationManager.AppSettings.Get("PARTNER_KEY");

        /// <summary>
        /// Partner Secret provided by TokBox
        /// </summary>
        public static string PARTNER_SECRET = ConfigurationManager.AppSettings.Get("PARTNER_SECRET");

        /// <summary>
        /// Environment to which you are pointing. Include trailing slash
        /// Sandbox: http://sandbox.tokbox.com/
        /// Production: http://api.tokbox.com/
        /// </summary>
        public static string API_SERVER = ConfigurationManager.AppSettings.Get("API_SERVER");

        /// <summary>
        /// Callback URL for redirecting successful OAuth based login
        /// </summary>
        public static string CALLBACK_URL = ConfigurationManager.AppSettings.Get("CALLBACK_URL");
    }
}