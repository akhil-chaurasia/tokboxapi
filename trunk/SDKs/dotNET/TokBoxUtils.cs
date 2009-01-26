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
 *       TokBox API Utilities        *
 *
 *************************************
 *
 * Original Java Code by Melih Onvural, August 2008

 * Converted to C# by Syedur Islam, November 2008

 *

 *

 */
using System;
using System.Collections;
using System.Collections.Generic;
using System.Text;

namespace TokBox
{
    public class TokBoxUtils
    {
        internal static string Join(List<string> stringCollection, string delimiter)
        {
            StringBuilder sb = new StringBuilder();
            int i = 0;
            
            foreach (string s in stringCollection)
            {
                i++;
                sb.Append(s);
                if (i != stringCollection.Count)
                {
                    sb.Append(delimiter);
                }
            }

            return sb.ToString();
        }
    }
}